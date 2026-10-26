<?php

namespace App\Domain\Services;

use App\Domain\Models\SitesModel;
use App\Helpers\Core\Result as Result;

class SitesServices
{
    public function __construct(private SitesModel $sites_model) {}

    public function doCreateSites(array $new_sites): Result
    {
        $error_data = [];
        $created_item_ids = [];
        //*Process the list of items to be created
        //TODO: VALIDATE THE FIELDS ACCORDING TO THE DATA INTEGRITY RULES WE HAVE IN THE DATABASE
        //TODO: Use a foreach to process the list of items toi be created.
        $new_site = $new_sites[0];
        $last_inserted_id = $this->sites_model->createSite($new_site);
        //TODO: add the $last_inserted_id to the $created_item_ids
        //?In case of  successful insertion: collect and return the last inserted ids
        return Result::success("The new site was added successfully!", $created_item_ids);

        //return Result::failure("An error occurred!", $error_data);`

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
