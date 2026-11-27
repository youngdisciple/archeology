<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\MuseumController;
use App\Controllers\SitesController;
use App\Controllers\ArtifactsController;
use App\Helpers\DateTimeHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\ScientistsController;
use Slim\Routing\RouteCollectorProxy;
use App\Middleware\AuthMiddleware;

return static function (Slim\App $app): void {

    // Routes without authentication check: /login, /token
    // $app->group('Non-Protected Routes', function (RouteCollectorProxy $group) {
    //     $group->get('/', [AboutController::class, 'handleAboutWebService']);
    //     $group->get('/register', [::class, '']);
    //     $group->get('/login', [::class, '']);
    //     $group->get('/token', [::class, '']);
    // })->add(::class);

    // Routes with authentication check
    $app->group('Protected Routes', function (RouteCollectorProxy $group) {
        //* About
        $group->get('/', [AboutController::class, 'handleAboutWebService']);

        //* Museum Routes
        // GET
        $group->get('/museums', [MuseumController::class, 'handleGetMuseums']);
        $group->get('/museums/{collection_id}', [MuseumController::class, 'handleGetMuseumById']);
        $group->get('/museums/{parent_collection_id}/scientists', [MuseumController::class, 'handleGetMuseumScientist']);
        $group->get('/museums/{parent_collection_id}/artifacts', [MuseumController::class, 'handleGetMuseumArtifact']);
        // POST
        $group->post('/museums', [MuseumController::class, 'handleCreateMuseum']);
        // PUT
        $group->put('/museums', [MuseumController::class, 'handleUpdateMuseum']);
        // DELETE
        $group->delete('/museums', [MuseumController::class, 'handleDeleteMuseum']);

        //* Sites Routes
        // GET
        $group->get('/sites', [SitesController::class, 'handleGetSites']);
        $group->get('/sites/{collection_id}', [SitesController::class, 'handleGetSitesById']);
        $group->get('/sites/{parent_collection_id}/cultures', [SitesController::class, 'handleGetSiteCulture']);
        $group->get('/sites/{parent_collection_id}/periods', [SitesController::class, 'handleGetSitePeriod']);
        // POST
        $group->post('/sites', [SitesController::class, 'handleCreateSites']);
        // PUT
        $group->put('/sites', [SitesController::class, 'handleUpdateSites']);
        // DELETE
        $group->delete('/sites', [SitesController::class, 'handleDeleteSites']);

    //* Museum routes
    $app->get('/museums', [MuseumController::class, 'handleGetMuseums']);
    $app->get('/museums/{collection_id}', [MuseumController::class, 'handleGetMuseumById']);
    $app->get('/museums/{parent_collection_id}/scientists', [MuseumController::class, 'handleGetMuseumScientist']);
    $app->get('/museums/{parent_collection_id}/artifacts', [MuseumController::class, 'handleGetMuseumArtifact']);
    $app->post('/museums', [MuseumController::class, 'handleCreateMuseum']);
    $app->put('/museums', [MuseumController::class, 'handleUpdateMuseum']);
    $app->delete('/museums', [MuseumController::class, 'handleDeleteMuseum']);

    //*ROUTES FOR SITES COLLECTION
    //!GET ROUTES FOR SITES
    $app->get('/sites', [SitesController::class, 'handleGetSites']);
    $app->get('/sites/{collection_id}', [SitesController::class, 'handleGetSitesById']);
    $app->get('/sites/{parent_collection_id}/cultures', [SitesController::class, 'handleGetSiteCulture']);
    $app->get('/sites/{parent_collection_id}/periods', [SitesController::class, 'handleGetSitePeriod']);
    $app->get('/sites/', [SitesController::class, 'getListOfObjects']);
    //!POST ROUTES FOR SITES
    $app->post('/sites', [SitesController::class, 'handleCreateSites']);
    //!PUT ROUTES FOR SITES
    $app->put('/sites', [SitesController::class, 'handleUpdateSites']);
    //!DELETE ROUTES FOR SITES
    $app->delete('/sites', [SitesController::class, 'handleDeleteSites']);

    //* ROUTES FOR ARTIFACTS COLLECTION
    // GET ROUTES FOR ARTIFACTS
    $app->get('/artifacts', [ArtifactsController::class, 'index']);
    $app->get('/artifacts/{artifact_id}', [ArtifactsController::class, 'show']);

    // POST
    $app->post('/artifacts', [ArtifactsController::class, 'createArtifacts']);

    // PUT
    $app->put('/artifacts', [ArtifactsController::class, 'updateArtifacts']);
    $app->put('/artifacts/{artifact_id}', [ArtifactsController::class, 'updateArtifact']);

    // DELETE
    $app->delete('/artifacts', [ArtifactsController::class, 'deleteArtifacts']);
    $app->delete('/artifacts/{artifact_id}', [ArtifactsController::class, 'deleteArtifact']);

        //* Scientist Routes
        // GET
        $group->get('/scientists', [ScientistsController::class, 'handleGetScientists']);
        $group->get('/scientists/{collection_id}', [ScientistsController::class, 'handleGetScientistById']);
        $group->get('/scientists/{parent_collection_id}/excavations', [ScientistsController::class, 'handleGetScientistExcavation']);
        // POST
        $group->post('/scientists', [ScientistsController::class, 'handleCreateScientists']);
        // PUT
        $group->put('/scientists', [ScientistsController::class, 'handleUpdateScientists']);
        // DELETE
        $group->delete('/scientists', [ScientistsController::class, 'handleDeleteScientists']);
    })->add(AuthMiddleware::class);


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
};
