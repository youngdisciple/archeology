<?php

namespace App\Exceptions ;

use Slim\Exception\HttpSpecializedException;

class BadRequestException extends HttpSpecializedException
{
    protected $code = 400;
    protected $message = "Bad request";
    protected string $title = "Invalid Resource request";
    protected string $description = "The following request caused some errors";
}
