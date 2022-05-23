<?php

namespace Koltsova\Router\Exceptions;

class NotImplementedException extends HttpException
{
    public $message = '<h1>This controller is not implemented!</h1>';
    public $code = 501;

}