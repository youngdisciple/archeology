<?php

namespace App\Domain\Services;

use App\Domain\Models\SitesModel;
use App\Helpers\Core\Result as Result;

/**
 * A service class that is useful for validating and performing operations on collections via the `insert()`, `update()`, `delete()` functions that are called from the model class
 */
class SitesService extends BaseService
{
    /**
     *
     * A constant array for Valicomb to validate inputs for the /sites resource
     * @var array
     */
    private const SITES_RULES = [
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
    ];
    public function __construct(SitesModel $sites_model)
    {
        parent::__construct($sites_model);
    }

    /**
     * calls the parent's reusable function `doCreateCollection()`
     * @param array $new_sites
     * @return Result
     */
    public function doCreateSites(array $new_sites): Result
    {
        return $this->doCreateCollection($new_sites, self::SITES_RULES, 'createSite');
    }

    /**
     * calls the parent's reusable function `doUpdateCollection`
     * @param array $updated_sites
     * @return Result
     */
    public function doUpdateSites(array $updated_sites): Result
    {
        return $this->doUpdateCollection($updated_sites, self::SITES_RULES, 'site_id', 'updateSites');
    }

    /**
     * calls the parent's reusable function `doDeleteCollection`
     * @param array $deleted_sites
     * @return Result
     */
    public function doDeleteSites(array $deleted_sites): Result
    {
        return $this->doDeleteCollection($deleted_sites, 'site_id', 'deleteSites');
    }
}
