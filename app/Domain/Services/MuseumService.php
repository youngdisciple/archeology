<?php

namespace App\Domain\Services;
use App\Domain\Models\MuseumModel;
use App\Helpers\Core\Result;

/**
 * MuseumService
 *
 * This class implements the logic and validation for museum operations.
 *
 * It defines the validation rules according to the database schema, and coordinates the creation, updating, and deletion of records through the model.
 */

class MuseumService extends BaseService
{
   private const MUSEUM_RULES = [
        'museum_name' => ['required', ['regex', '/^[a-z ]+$/ix']],
        'city' => ['optional', ['regex', '/^[a-z ]+$/ix']],
        'foundation_date' => ['optional', 'date', ['dateFormat', 'Y-m-d']],
        'website' => ['optional', 'url'],
        'museum_type' => ['optional', ['regex', '/^[a-z ]+$/ix'] ]
    ];


    /**
     * __construct
     *
     * Initialize the service with dependency on the model
     *
     * @param MuseumModel $museumModel
     * @return void
     */

    public function __construct(MuseumModel $museumModel)
    {
        parent::__construct($museumModel);
    }

    /**
     * doCreateMuseums
     *
     * validate and create the submitted data through a reusable function in the base service
     *
     * @param array $museums The submitted data to be created
     * @return Result Result indicating success or containing validation errors
     */

    public function doCreateMuseums(array  $museums): Result {

        return $this->doCreateCollection($museums, self::MUSEUM_RULES, 'createMuseum');
    }


    //TODO: Add two new public methods: One for the update operation and one for delete operation.

    /**
     * doUpdateMuseum
     *
     * validate and update the submitted data through a reusable function in the base service
     *
     * @param array $museums The submitted data to be updated
     * @return Result Result indicating success or containing validation errors
     */

    public function doUpdateMuseum(array $museums) : Result {

        return $this->doUpdateCollection($museums, self::MUSEUM_RULES, 'museum_id', 'updateMuseums');
    }

    /**
     * doDeleteMuseum
     *
     * delete the submitted data, by its museum ID, through a reusable function in the base service
     *
     * @param array $museums The submitted data to be deleted
     * @return Result Result indicating success or containing validation errors
     */

    public function doDeleteMuseum(array $museums) : Result {

        return $this->doDeleteCollection($museums, 'museum_id', 'deleteMuseums');
    }

}
