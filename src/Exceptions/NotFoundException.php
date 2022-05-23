<?php

namespace Koltsova\Router\Exceptions;

class NotFoundException extends HttpException
{
    public $message = '<h1>Page is not found!</h1>';
    public $code = 404;

}