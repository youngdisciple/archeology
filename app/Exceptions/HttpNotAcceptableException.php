<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;


class HttpNotAcceptableException extends HttpSpecializedException
{
    protected $code = 406;
    protected $message = 'The requested response format is not supported.';
    protected string $title = '406 Not Acceptable';
    protected string $description = 'The Accept header does not match any supported media type.';
}
