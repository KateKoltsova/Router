<?php

namespace Koltsova\Router\Exceptions;

class MethodNotAllowedException extends HttpException
{
    public $message = '<h1>This method is not exists!</h1>';
    public $code = 405;

}