<?php

namespace App\Domain\Services;

use App\Domain\Models\ScientistsModel;
use App\Helpers\Core\Result;

use function DI\add;

class ScientistsService extends BaseService
{
    public function __construct(private ScientistsModel $scientistsModel) {}

    public function doCreateScientists(array $new_scientists): Result
    {
        $errors = [];
        $created_item_ids = [];

        //TODO: Use a for each loop to process the list of items to be created
        //TODO: YOU MUST VALIDATE THE ITEM FIELDS ACCORDING TO THE DATA INTEGRITY RULES YOU HAVE IN DATABASE SCHEMA
        //!- Add error details to $errors: more on this next week
        $new_scientist = $new_scientists[0]; // this statement inserts ONLY one item
        $last_inserted_id = (int) $this->scientistsModel->createScientist($new_scientist);
        //TODO: add the $last_inserted_id to $created_item_ids
        $created_item_ids = add($last_inserted_id);

        //NOTE: can return last inserted id
        //?NOTE: In case of a successful insertion -> collect and return the last inserted IDs
        return Result::success("New scientists were created successfully!", $created_item_ids);

        return Result::failure("Error Message!", $errors);
    }

    //TODO: 2 new public methods: One for update, one for delete
}
