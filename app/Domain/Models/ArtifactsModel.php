<?php

namespace App\Domain\Models;

class ArtifactsModel extends BaseModel
{
    // Takes filters (optional) and returns a collection of artifacts
    //
    // @param array $filters
    // @return array
    function getArtifacts(array $filters): array
    {
        $sql = "SELECT * FROM artifacts WHERE 1 = 1";
        $conditions = [];

        // NOTE: Filter by id is listed on the filtering table in the document
        // but will be dropped (the show method handles that)


        // input : filters >> [
        //      over_estimated_value => 2
        //      under_estimated_value => 200
        // ]
        //
        // output the respective sql query to append

        $sqlQueries = [
            'artifact_name' => "artifact_name LIKE CONCAT('%', :artifact_name, '%')",
            'over_estimated_value' => "estimated_value >= :over_estimated_value",
            'under_estimated_value' => "estimated_value <= :over_estimated_value",
            'artifact_type' => "artifact_type = LIKE CONCAT('%', :artifact_type, '%')",
            'museum_id' => "museum_id = :museum_id",
            'condition' => "condition = :condition"
        ];

        // the filters received are already validated,,,
        foreach ($filters as $k => $v) {
            $sql .= " AND {$sqlQueries[$k]}";
            $conditions[$k] = $v;
        }

        return $this->paginate($sql, $conditions);
    }

    // TODO:
    function getArtifactsById(int $artifact_id): array | False
    {
        $sql = "SELECT * FROM artifacts WHERE artifact_id = :artifact_id";

        return $this->fetchSingle($sql, ['artifact_id' => $artifact_id]);
    }
}
