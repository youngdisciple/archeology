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
        // // TODO 1: Create a Logger instance with the channel name 'access'.
        // $logger = new Logger('access');

        // // TODO 2: Add a StreamHandler using APP_BASE_PATH to build the path to var/logs/access.log.
        // // TODO 3: Set the logger's time zone and customize the log format using a LineFormatter.
        // //         Apply the formatter to the StreamHandler.
        // $lf = new LineFormatter("[%datetime%] %channel%.%level_name%: %message% %context%\n", 'Y-m-d H:i:s');
        // $logger->pushHandler(
        //     (new StreamHandler(APP_BASE_PATH . '/var/logs/app.log', Level::Info))->setFormatter($lf)
        // )->setTimezone(new DateTimeZone('EST'));


        // TODO 5: Pass the request to the next middleware/handler and store the response.
        // IMPORTANT: This call must come before logging the response status code.
        $response = $handler->handle($request);


        // TODO 6: Log an INFO message with the method, URI, IP, and response status code as context.
        $logArr = [
            'method' => $request->getMethod(),
            'uri' => $request->getUri()->getPath(),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'status' => $response->getStatusCode()
        ];
        $extra = $request->getQueryParams();
        // $logger->info('Incoming Request', $logArr);
        $record = implode(', ', $logArr);
        LogHelper::logAccess($record, $extra);

        // TODO 7: Return the response.
        return $response;
    }
}
