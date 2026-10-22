<?php

namespace App\Domain\Services;

use App\Domain\Models\SitesModel;

class SitesServices
{
    public function __construct(private SitesModel $sites_model, private SitesServices $sites_services) {}
}
