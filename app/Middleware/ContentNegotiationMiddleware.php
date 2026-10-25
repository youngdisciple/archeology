<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exceptions\HttpNotAcceptableException;
use App\Helpers\AcceptHeaderParser;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

class ContentNegotiationMiddleware implements MiddlewareInterface
{
    /**
     * Validates the requested response representation
     * before processing the request.
     *
     * @param Request $request Incoming HTTP request.
     * @param RequestHandler $handler Next request handler.
     * @return ResponseInterface
     */
    public function process(Request $request, RequestHandler $handler
    ): ResponseInterface {

        // Optional: Handle the incoming request
        //* ...Before middleware!

        //* check if the web service can produce  the requested representation (data format).
        $header = $request->getHeaderLine('Accept');

        $supportedTypes = [
            'application/json'
        ];

        $format = AcceptHeaderParser::getBestMatch($header, $supportedTypes);

        if ($format === null) {
            throw new HttpNotAcceptableException($request,
                "This data format isn't supported."
            );
        }

        $request = $request->withAttribute('negotiatedFormat',$format);

        //! DO NOT remove or change the following statements.
        // Invoke the next middleware and get response
        $response = $handler->handle($request);

        return $response
            ->withHeader('Content-Type', $format)
            ->withHeader('Vary', 'Accept');
    }
}
