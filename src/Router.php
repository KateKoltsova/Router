<?php

namespace Koltsova\Router;

use Aigletter\Contracts\Routing\RouteInterface;

/**
 * Class router implements the RouteInterface from the Aigletter\Contracts package.
 * Contains a config file with actions for specific URLs.
 * Allows you to pass the necessary parameters to the corresponding methods of the called classes.
 * Parameters are determined using reflection from a GET request.
 * The router is designed as a composer package according to the psr-4 standard, and posted on packagist.
 */
class Router implements RouteInterface
{
    /**
     * Contains routing paths and actions for them.
     * @var array
     */
    public array $router = [];

    /**
     * Contains value of necessary parameters for methods using in action.
     * @var array
     */
    public array $args = [];

    /**
     * The main function of the class. Executes a callback of an action predefined in the array $router.
     * @param string $uri
     * @return callable
     * @throws \Exception
     */
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

    /**
     * Adds a pair of path-action values from the config file to the array $router.
     * @param string $uri
     * @param $method
     * @return string
     * @throws \Exception
     */
    public function addRoute(string $uri, $method): string
    {
        if (empty($uri) || empty($method)) {
            throw new \Exception("Routing parameters can't be empty!" . '</br>');
        } else {
            $this->router[$uri] = $method;
            return "Adding action for uri $uri successful!" . '</br>';
        }
    }

    /**
     * Gets an array of required parameters for the method called in the URL-action.
     * Gets the values of these parameters from the GE-request (if they exist)
     * or uses the default values (for optional parameters)
     * and fills in the array $args.
     * @param $className
     * @param $methodName
     * @return void
     * @throws \ReflectionException
     * @throws \Exception
     */
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