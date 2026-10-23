<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\SitesController;
use App\Helpers\DateTimeHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\ScientistsController;

return static function (Slim\App $app): void {

    // Routes without authentication check: /login, /token

    //* ROUTE: GET /
    $app->get('/', [AboutController::class, 'handleAboutWebService']);

    //* NOTE: callback naming pattern: handle<ActionName>, e.g. handleGetPlayers
    //* ROUTE: GET /players
    //$app->get('/players', [PlayersController::class, 'handleGetPlayers']);

    //* ROUTE: GET /scientists
    $app->get('/scientists', [ScientistsController::class, 'handleGetScientists']);

    //* ROUTE: GET /scientists
    $app->post('/scientists', [ScientistsController::class, 'handleCreateScientists']);

    //* ROUTE: GET /ping
    $app->get('/ping', function (Request $request, Response $response, $args) {

        $payload = [
            "greetings" => "Reporting! Hello there!",
            "now" => DateTimeHelper::now(DateTimeHelper::Y_M_D_H_M),
        ];
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR));
        return $response;
    });

    // Example route to test error handling.
    $app->get('/error', function (Request $request, Response $response, $args) {
        throw new \Slim\Exception\HttpNotFoundException($request, "Something went wrong");
    });

    //* ROUTE: GET /phpinfo -> Display PHP configuration (useful for Docker)
    //* Docker URL: http://localhost:8080/phpinfo
    $app->get('/phpinfo', function (Request $request, Response $response, $args) {
        ob_start();
        phpinfo();
        $info = ob_get_clean();
        $response->getBody()->write($info);
        return $response->withHeader('Content-Type', 'text/html');
    });


    //*ROUTES FOR SITES COLLECTION
    //!GET ROUTES FOR SITES
    $app->get('/sites', [SitesController::class, 'handleGetSites']);
    $app->get('/sites/{collection_id}', [SitesController::class, 'handleGetSitesById']);
    $app->get('/sites/{parent_collection_id}/cultures', [SitesController::class, 'handleGetSiteCulture']);
    $app->get('/sites/{parent_collection_id}/periods', [SitesController::class, 'handleGetSitePeriod']);
    //!POST ROUTES FOR SITES
    $app->post('/sites', [SitesController::class, 'handleCreateSites']);
};
