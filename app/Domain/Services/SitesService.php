<?php

namespace App\Domain\Services;

use App\Domain\Models\SitesModel;
use App\Helpers\Core\Result as Result;
use Frostybee\Valicomb\Validator;

class SitesService
{
    public function __construct(private SitesModel $sites_model) {}

    public function doCreateSites(array $new_sites): Result
    {
        $error_data = [];
        $created_item_ids = [];
        //*Process the list of items to be created
        //*VALIDATE THE FIELDS ACCORDING TO THE DATA INTEGRITY RULES WE HAVE IN THE DATABASE
        //VALIDATION GOES HERE
        foreach ($new_sites as $site_entry) {
            $sites_rules = new Validator($site_entry);
            $sites_rules->forFields([
                'site_name' => [
                    'required',
                    ['lengthBetween', 1, 85],
                    ['regex', "/^[\p{L}\p{N}][\p{L}\p{N}\s'.,()\-]*$/u"]
                ],

                'site_type' => [
                    'required',
                    ['lengthBetween', 1, 25],
                    ['regex', '/^[\p{L}][\p{L}\s\-]*$/u']
                ],

                'discovery_date' => [
                    'required',
                    'date',
                    ['dateFormat', 'Y-m-d'],
                    'past'
                ],

                'estimated_age' => [
                    'optional',
                    'integer',
                    ['between', 0, 60000000]
                ],

                'longitude' => [
                    'required',
                    'numeric',
                    ['decimalPlaces', 6]
                ],

                'latitude' => [
                    'required',
                    'numeric',
                    ['decimalPlaces', 6]
                ],

                'country' => [
                    'required',
                    ['lengthBetween', 1, 45],
                    ['regex', "/^[\p{L}][\p{L}\s'.\-]*$/u"]
                ],

                'region' => [
                    'required',
                    ['lengthBetween', 1, 85],
                    ['regex', "/^[\p{L}][\p{L}\s'.,()\-]*$/u"]
                ],

                'description' => [
                    'required',
                    ['lengthBetween', 1, 1000]
                ],

                'is_protected' => [
                    'required',
                    'boolean'
                ],

                'period_id' => [
                    'positive',
                    'integer',
                    ['min', 1],
                    ['max', 100]
                ],

                'culture_id' => [
                    'positive',
                    'integer',
                    ['min', 1],
                    ['max', 100]
                ]
            ]);
            if (!$sites_rules->validate()) {
                $error_data[] = $sites_rules->getErrors();
            }
        }
        if (!empty($error_data)) {
            return Result::failure("An error occurred!", $error_data);
        } else {
            //* Use a foreach to process the list of items to be created.
            foreach ($new_sites as $site) {
                $last_inserted_id = $this->sites_model->createSite($site);
                $created_item_ids[] = $last_inserted_id;
            }
        }
        //?In case of  successful insertion: collect and return the last inserted ids
        return Result::success("The new site was added successfully!", $created_item_ids);
    }

    public function doUpdateSites(array $updated_sites): Result
    {

        //*You need to unset the item id from the array to be passed to the model before calling the model's update function
        foreach ($updated_sites as $site) {
            $site_id = $site['site_id'];
            unset($site['site_id']);
            $this->sites_model->updateSites($site, $site_id);
        }
        return Result::success("The site was updated successfully!");
    }

    public function doDeleteSites(array $deleted_sites): Result
    {
        foreach ($deleted_sites as $site) {
            $site_id = $site['site_id'];
            $this->sites_model->deleteSites($site_id);
        }
        return Result::success("The site was deleted successfully!");
    }
}
