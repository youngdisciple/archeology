<?php

namespace App\Controllers;

use App\Domain\Models\SitesModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Services\SitesService;

class SitesController extends BaseController
{
    public function __construct(private SitesService $sites_services, private SitesModel $sites_model)
    {
        parent::__construct($sites_model, $sites_services);
    }


    //*callbacks for GET
    /**
     * Handles the request and response logic for sending the archeological site collection
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handleGetSites(Request $request, Response $response, array $uri_args): Response
    {
        $api_data = $this->sites_model->
        return $this->handleGetCollection($request, $response, "getSites", $uri_args);
    }

    /**
     * Handles the request and response logic for sending a singleton site resource
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function handleGetSitesById(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetCollectionById($request, $response, $args, "getSitesById");
    }

    /**
     * Handles the request and response logic for sending the archeological site sub-collection: historical periods
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function handleGetSitePeriod(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetSubCollection($request, $response, $args, "getSitePeriod");
    }

    /**
     * Handles the request and response logic for sending the archeological site sub-collection: cultures
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function handleGetSiteCulture(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetSubCollection($request, $response, $args, "getSiteCulture");
    }


    //*callback for POST
    /**
     * calls the `BaseController`'s universal reusable function `handleCollectionOperation()`
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handleCreateSites(Request $request, Response $response): Response
    {
        return $this->handleCollectionOperation($request, $response, 'doCreateSites', 'The list of new sites was properly created!', 'Something went wrong, insertion of the new site was aborted', 201);
    }

    /**
     * calls the `BaseController`'s universal reusable function `handleCollectionOperation()`
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    //*callback for PUT
    public function handleUpdateSites(Request $request, Response $response): Response
    {
        return $this->handleCollectionOperation($request, $response, 'doUpdateSites', "Sites got updated successfully!", "Something went wrong, the update on sites was aborted");
    }

    /**
     * calls the `BaseController`'s universal reusable function `handleCollectionOperation()`
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    //*callback for DELETE
    public function handleDeleteSites(Request $request, Response $response): Response
    {
        return $this->handleCollectionOperation($request, $response, 'doDeleteSites', "The site was deleted successfully!", "Something went wrong, the site couldn't be deleted and was aborted!");
    }
}
