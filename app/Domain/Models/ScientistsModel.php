<?php

namespace App\Domain\Models;

use App\Domain\Models\BaseModel;

/**
 * The model that handles the sql statements for the api
 */
class ScientistsModel extends BaseModel
{
    /**
     * Fetches the list of all scientists
     * @param array $filters The array that features all filters for the scientists table
     * @return array the array that gives all the scientists according to the filtered result
     */
    public function getScientists(array $filters): array
    {
        $filterConfig = [

        ];

        return $filters;
    }

    /**
     * Returns one scientist based off the provided ID
     * @param int $scientist_id The user provided ID for the scientist they want
     * @return array|false returns the scientist that the user requested or false if the scientist does not exist
     */
    public function getScientistById(int $scientist_id): array | false
    {
        $sql = "SELECT * FROM scientist WHERE scientist_id = :scientist_id";

        return $this->fetchSingle(
            $sql,
            ["scientist_id" => $scientist_id]
        );
    }

    /**
     * Creates scientist based on user provided data
     * @param array $scientist The JSON array that contains all of the data for new scientists
     */
    public function createScientist(array $scientist)
    {
        return $this->insert("scientist", $scientist);
    }
}
