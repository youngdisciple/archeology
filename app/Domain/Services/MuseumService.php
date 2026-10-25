<?php

namespace App\Domain\Services;
use App\Domain\Models\MuseumModel;
use App\Helpers\Core\Result;

class MuseumService extends BaseService
{
    public function __construct(private MuseumModel $museumModel) {}

    public function doCreateMuseums(array  $newMuseums): Result {

        $errors = [];
        $created_item_ids =[];

        //* 1. Process the list of items to be created
        //TODO: Validate the item fields according to the data integrity rules you have in the database schema

        //TODO: USE a foreach to process the list
        //! Add error details to the $errors
        foreach ($newMuseums as $newMuseum) {

        }

        // $newMuseum = $newMuseums[0]; only inserts one item
        $last_inserted_id = $this->museumModel->createMuseum($newMuseum);

        //TODO: add the $last_inserted_id to the $created_items_ids array

        //NOTE: you can return the last inserted ID.
        //? NOTE: In case of a successful insertion -> collect and return the last inserted IDs
        return Result::success("The new museums were created successfully!", $created_item_ids);
        return Result::failure("Error.", $errors);

        //TODO: Add two new public methods: One for the update operation and one for delete operation.
    }

}
