<?php

namespace Koltsova\Router;

use Aigletter\Contracts\ComponentFactory;

/**
 * Class Router factory extends ComponentFactory from the Aigletter\Contracts package.
 * Using for creating object of Router class and writing path-action from config file with addRoute function of Router.
 */
class RouterFactory extends ComponentFactory
{
    /**
     * Consist link to Router object.
     * @var Router
     */
    protected static $router;

    /**
     * The main function of class.
     * Creating object of Router class and writing path-action from config file with addRoute function of Router.
     * @return Router
     * @throws \Exception
     */
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