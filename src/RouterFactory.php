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
     * The main function of class.
     * Creating object of Router class and writing path-action from config file with addRoute function of Router.
     * @return Router
     * @throws \Exception
     */
    protected function createConcreteComponent()
    {
        $router = new Router();

        if (empty($this->arguments['routes'])) {
            throw new \Exception('Choose routes directory!');
        } else {
            require_once $this->arguments['routes'];
        }
        return $router;
    }
}