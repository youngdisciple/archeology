<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Helpers\LogHelper;
use DateTimeZone;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Formatter\LineFormatter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LoggingMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        //*Pass the request to the next middleware/handler and store the response.
        // IMPORTANT: This call must come before logging the response status code.
        $response = $handler->handle($request);


        //* Log an INFO message with the method, URI, IP, and response status code as context.
        $logArr = [
            'method' => $request->getMethod(),
            'uri' => $request->getUri()->getPath(),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'status' => $response->getStatusCode()
        ];
        $extra = $request->getQueryParams();
        $record = implode(', ', $logArr);
        LogHelper::logAccess($record, $extra);

        //* Return the response.
        return $response;
    }
}
