<?php

namespace App\Domain\Services;

use App\Domain\Models\SitesModel;
use App\Helpers\Core\Result as Result;

class SitesServices
{
    public function __construct(private SitesModel $sites_model) {}

    public function doCreateSites(array $new_sites): Result
    {
        //*Process the list of items to be created
        //TODO: VALIDATE THE FIELDS ACCORDING TO THE DATA INTEGRITY RULES WE HAVE IN THE DATABASE
        $new_site = $new_sites[0];
        $last_inserted_id = $this->sites_model->createSite($new_site);
        return Result::success("The new site was added successfully!");
    }

    //TODO: Add 2 new public methods: one for UPDATE and the other for DELETE
    public function doUpdateSites(array $updated_site): Result
    {
        return Result::success("The site was updated successfully!");
    }

    public function doDeleteSites(array $deleted_site): Result
    {
        return Result::success("The site was deleted successfully!");
    }
}
