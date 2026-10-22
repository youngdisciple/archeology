<?php

namespace App\Domain\Services;
use App\Domain\Models\MuseumModel;
use App\Helpers\Core\Result;

class MuseumService extends BaseService
{
    public function __construct(private MuseumModel $museumModel) {}

    public function doCreateMuseums(array  $newMuseums): Result {
        //* 1. Process the list of items to be created
        //TODO: Validate the item fields according to the data integrity rules you have in the database schema
        $newMuseum = $newMuseums[0];
        $last_inserted_id = $this->museumModel->createMuseum($newMuseum);

        //NOTE: you can return the last inserted ID.
        return Result::success("the new museums were created successfully!");

        //TODO: Add two new public methods: One for the update operation and one for delete operation.
    }

}
