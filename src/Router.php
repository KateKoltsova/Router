<?php

namespace Koltsova\Router;

use Aigletter\Contracts\Routing\RouteInterface;

class Router implements RouteInterface
{

    public array $router = [];

    public function route(string $uri): callable
    {
        $usableAction = array_filter($this->router, function ($path) use ($uri) {
            return $path === $uri;
        }, ARRAY_FILTER_USE_KEY);
        if (empty($usableAction)) {
            throw new \Exception("Searching page is not found!" . '</br>');
        } else {
            if (is_array($usableAction[$uri])) {
                [$className, $methodName] = $usableAction[$uri];
                return function () use ($className, $methodName) {
                    $class = new $className;
                    return call_user_func([$class, $methodName]);
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
}