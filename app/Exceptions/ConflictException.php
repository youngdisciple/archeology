<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

/**
 * For PDO and SQL errors that 
 */
class ConflictException extends HttpSpecializedException
{
    protected $code = 409;
    protected $message = "A conflict occurred";
    protected string $title = "Conflict";
    protected string $description = "The server ran into a conflict with the database when trying to run your request.";
}
