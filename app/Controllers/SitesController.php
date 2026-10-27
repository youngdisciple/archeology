<?php

namespace App\Controllers;

use App\Domain\Models\SitesModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Services\SitesServices;

class SitesController extends BaseController
{
    public function __construct(private SitesServices $sites_services, private SitesModel $sites_model)
    {
        parent::__construct($sites_model);
    }


    //*callbacks for GET
    /**
     * Handles the request and response logic for sending the archeological site collection
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handleGetSites(Request $request, Response $response): Response
    {
        return $this->handleGetCollection($request, $response, "getSites");
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


    //*callbacks for POST
    /**
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handleCreateSites(Request $request, Response $response): Response
    {
        echo "test";
        //*Retrieve the representation of the collection items that need to be created
        $payload = $request->getParsedBody();
        //*Pass the list of new collection items to the Service component to be validated and then inserted into the database
        $result = $this->sites_services->doCreateSites($payload);
        $response_data = [];
        if ($result->isSuccess()) {
            $code = 201;
            $response_data = [
                "code" => $code,
                "message" => "The list of new sites was properly created!"
            ];
            return $this->renderJson($response, $response_data, $code);
        }
        if ($result->isFailure()) {
            $code = 400;
            $response_data = [
                "code" => $code,
                "status" => "error",
                "message" => "Something went wrong, insertion of the new site was aborted",
                "details" => $result->getErrors()
            ];
            return $this->renderJson($response, $response_data);
        }
        return $response;
    }
}
