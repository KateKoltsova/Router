<?php

namespace Koltsova\Router\Exceptions;

class RequestException extends HttpException
{
    public $message = '<h1>Request without parameters!</h1>';
    public $code = 400;

}