<?php

namespace App\Domain\Services;
use App\Domain\Models\MuseumModel;
use App\Helpers\Core\Result;

class MuseumService extends BaseService
{
    public function __construct(private MuseumModel $museumModel) {}

    public function doCreateMuseums(array  $museums): Result {

        $errors = [];
        $created_item_ids =[];

        //* 1. Process the list of items to be created
        //TODO: Validate the item fields according to the data integrity rules you have in the database schema
        //TODO: USE a foreach to process the list
        //! Add error details to the $errors
        foreach ($museums as $newMuseums) {
            // required field + varchar(150)
            if (empty($newMuseums['museum_name'])){
                $errors[] = "The field 'museum_name' is required";
            } elseif (strlen($newMuseums['museum_name']) > 150 ) {
                $errors[] = "The field 'museum_name' cannot exceed 150 characters";
            }

            // optional field + varchar(100)
            if (isset($newMuseums['city']) && strlen($newMuseums['city']) > 100 ) {
                $errors[] = "The field 'city' cannot exceed 100 characters";
            }

            // optional field + format = YYYY-MM-DD
            if (isset($newMuseums['foundation_date']) ) {
                $date = $newMuseums['foundation_date'];
                $parts = explode('-', $date);
                if(!checkdate($parts[1], $parts[2], $parts[0])){
                    $errors[] = "Enter a valid date in the format YYYY-MM-DD";
                }
            }
        }

        // $newMuseum = $newMuseums[0]; only inserts one item
        $last_inserted_id = $this->museumModel->createMuseum($newMuseums);

        //TODO: add the $last_inserted_id to the $created_items_ids array
        $created_item_ids [] = $last_inserted_id;
        //NOTE: you can return the last inserted ID.
        //? NOTE: In case of a successful insertion -> collect and return the last inserted IDs
        return Result::success("The new museums were created successfully!", $created_item_ids);
    }


    //TODO: Add two new public methods: One for the update operation and one for delete operation.

    public function doUpdateMuseum(array $museums) : Result {

        $errors = [];
        //TODO: unset/remove the museum id from the array to be passed to the model before calling the model's update method
        foreach($museums as $museum){
            //TODO: validate
            $museum_id = $museum['museum_id'];
            unset($museum['museum_id']);
            $this->museumModel->updateMuseum($museum, $museum_id);
        }

        return Result::success("The museums have been updated successfully!", $museums);
    }

    public function doDeleteMuseum(array $museums) : Result {

        $errors = [];
        foreach($museums as $museum){
            //TODO: validate
            $museum_id = $museum['museum_id'];
            unset($museum['museum_id']);
            $this->museumModel->deleteMuseum($museum_id, 1);
        }

        return Result::success("The museum has been deleted successfully!", $museums);
    }

}
