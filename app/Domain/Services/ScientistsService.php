<?php

namespace App\Domain\Services;

use App\Domain\Models\ScientistsModel;
use App\Helpers\Core\Result;

class ScientistsService extends BaseService
{
    public function __construct(private ScientistsModel $scientistsModel) {}

    public function doCreateScientists(array $new_scientists): Result
    {
        //TODO: YOU MUST VALIDATE THE ITEM FIELDS ACCORDING TO THE DATA INTEGRITY RULES YOU HAVE IN DATABASE SCHEMA
        $new_scientist = $new_scientists[0];
        $last_inserted_id = $this->scientistsModel->createScientist($new_scientist);

        //NOTE: can return last inserted id
        return Result::success("New scientists were created successfully!");
    }

    //TODO: 2 new public methods: One for update, one for delete
}
