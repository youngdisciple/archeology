<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Helpers\Core\AppSettings;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;

class AuthMiddleware implements MiddlewareInterface
{
    private AppSettings $appSettings;

    public function __construct(AppSettings $appSettings)
    {
        $this->appSettings = $appSettings;
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // TODO 1: Get the JWT secret and algorithm from $this->appSettings->get('jwt').
        $jwt_settings = $this->appSettings->get('jwt');
        $key = $jwt_settings['secret'];
        $algo  = $jwt_settings['algorithm'];

        // TODO 2: Get the Authorization header from the request.
        // If the header is missing or empty, throw an HttpUnauthorizedException
        // with the message "Token required".
        $authHeader = $request->getHeader("Authorization");
        if (empty($authHeader)) {
            throw new HttpUnauthorizedException(
                $request,
                "Token required."
            );
        }
        // dd($authHeader);

        // TODO 3: Extract the token from the header.
        //         The expected format is "Bearer <token>".
        //         The scheme name is case-insensitive: accept "bearer" too.
        //         If the format is invalid, throw an HttpUnauthorizedException
        //         with the message "Invalid token format".
        $jwt = "";

        $clean_jwt = str_replace("Bearer ", "", $jwt);

        dd($clean_jwt);

        // TODO 4: Decode and validate the token using JWT::decode().
        //         Pass the token along with a new Key object: new Key($secret, $algorithm).
        //         Wrap this in a try/catch block. Find the exceptions JWT::decode()
        //         can throw in the "Exception Handling" section of the library's
        //         README, and import the ones you catch.
        //         For each invalid-token case (e.g., expired token, invalid signature),
        //         throw an HttpUnauthorizedException with a clear message.

        // TODO 5: If decoding succeeds, attach the decoded claims to the request
        //         using withAttribute(). Add at minimum:
        //         - 'token_user_id': the user ID from your token's claims
        //         - 'token_email': the email from your token's claims
        //         IMPORTANT: These calls must come BEFORE calling $handler->handle().

        // TODO 6: Pass the modified request to the next handler and return the response.


    }
}
