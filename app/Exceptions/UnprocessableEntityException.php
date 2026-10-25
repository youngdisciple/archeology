<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class UnprocessableEntityException extends HttpSpecializedException
{
    protected $code = 422;
    protected $message = "Unprocessable Entity";
    protected string $title = "422 Unprocessable Content";
    protected string $description = "The server was unable to process the contained instructions";
}
