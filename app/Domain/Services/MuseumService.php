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
    }


    //TODO: Add two new public methods: One for the update operation and one for delete operation.

    public function doUpdateMuseums(array $museums) : Result {

        $errors = [];
        //TODO: unset/remove the museum id from the array to be passed to the model before calling the model's update method
        foreach($museums as $museum){
            //TODO: validate
            $museum_id = $museum['museum_id'];
            unset($museum['museum_id']);
            $this->museumModel->updateMuseum($museum, $museum_id);
        }

        // $updatedMuseums = $this->museumModel->updateMuseum($museums, $conditions);

        return Result::success("The museums have been updated successfully!", $museums);
    }

    public function doDeleteMuseums(array $conditions, int $limit) : Result {

        $errors = [];
        //validate
        //process

        $museumsToDelete = $this->museumModel->deleteMuseum($conditions, $limit);

        return Result::success("The museums have been updated successfully!", $museumsToDelete);
    }

}
