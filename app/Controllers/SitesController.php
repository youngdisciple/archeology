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
    public function handleGetSites(Request $request, Response $response): Response
    {
        return $this->handleGetCollection($request, $response, "getSites");
    }

    public function handleGetSitesById(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetCollectionById($request, $response, $args, "getSitesById");
    }

    public function handleGetSitePeriod(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetSubCollection($request, $response, $args, "getSitePeriod");
    }

    public function handleGetSiteCulture(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetSubCollection($request, $response, $args, "getSiteCulture");
    }


    //*callbacks for POST
    public function handleCreateSites(Request $request, Response $response): Response
    {
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
