<?php

namespace App\Controllers;

use App\Domain\Models\SitesModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SitesController extends BaseController
{
    public function __construct(private SitesModel $sites_model) {}

    public function handleGetSites(): Response {}
}
