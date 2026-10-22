<?php

namespace App\Controllers;

use App\Domain\Models\SitesModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SitesController extends BaseController
{
    public function __construct(private SitesModel $sites_model) {}

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
}
