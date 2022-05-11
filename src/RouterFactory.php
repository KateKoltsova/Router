<?php

namespace Koltsova\Router;

use Aigletter\Contracts\ComponentFactory;

class RouterFactory extends ComponentFactory
{
    protected static $router;

    protected function createConcreteComponent()
    {
        if (self::$router === null) {
            self::$router = new Router();
        }
        $actions = require_once __DIR__.'/../config/actions.php';
        foreach ($actions as $path => $action) {
            self::$router->addRoute($path, $action);
        }
        return self::$router;
    }
}