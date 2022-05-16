<?php

namespace Koltsova\Router;

use Aigletter\Contracts\Routing\RouteInterface;

class Router implements RouteInterface
{

    public array $router = [];

    public array $args = [];

    public function route(string $uri): callable
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $usableAction = array_filter($this->router, function ($path) use ($uri) {
            return $path === $uri;
        }, ARRAY_FILTER_USE_KEY);
        if (empty($usableAction)) {
            throw new \Exception("Searching page is not found!" . '</br>');
        } else {
            if (is_array($usableAction[$uri])) {
                [$className, $methodName] = $usableAction[$uri];
                $this->getMethodParameters($className, $methodName);
                return function () use ($className, $methodName) {
                    $class = new $className;
                    return call_user_func_array([$class, $methodName], $this->args);
                };
            } else {
                return $usableAction[$uri];
            }
        }
    }

    public function addRoute(string $uri, $method): string
    {
        if (empty($uri) || empty($method)) {
            throw new \Exception("Routing parameters can't be empty!" . '</br>');
        } else {
            $this->router[$uri] = $method;
            return "Adding action for uri $uri successful!" . '</br>';
        }
    }

    public function getMethodParameters($className, $methodName)
    {
        $reflection = new \ReflectionMethod($className, $methodName);
        $parameters = $reflection->getParameters();
        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            $getType = $parameter->getType();
            if ($getType && !$getType->isBuiltin()) {
                $className = $getType->getName();
                $arg = new $className();
            } else {
                if (empty($_GET[$name])) {
                    if ($parameter->isOptional()) {
                        $param = $parameter->getDefaultValue();
                    } else {
                        throw new \Exception("Your request is not consist required parameters!" . '</br>');
                        die();
                    }
                } else {
                    $param = $_GET[$name];
                }
                if ($getType && $getType->getName() == 'array') {
                    if (is_array($param)) {
                        $array = $param;
                    } else {
                        $array = explode(",", $param);
                    }
                    $arg = $array;
                } else {
                    if ($getType) {
                        settype($param, $getType->getName());
                    }
                    $arg = $param;
                }
            }
            $this->args [] = $arg;
        }
    }
}