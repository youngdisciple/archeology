<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class MediaTypeException extends HttpSpecializedException
{
    protected $code = 415;
    protected $message = "Unable to parse media type";
    protected string $title = "Unprocessable Media Type";
    protected string $description = "The server was unable to parse the format of the request.";
}
