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
        $sort_by = $filters['sort_by'] ?? 'artifact_id';

        // NOTE: conditon is a sql term so it needs backticks
        if ($sort_by === 'condition') $sort_by = '`condition`';

        $order = $filters['order'] ?? 'ASC';

        $sql = "SELECT * FROM artifacts WHERE 1 = 1";
        $conditions = [];

        // TODO: Sorting

        $sqlQueries = [
            'artifact_name' => "artifact_name LIKE CONCAT('%', :artifact_name, '%')",
            'over_estimated_value' => "estimated_value >= :over_estimated_value",
            'under_estimated_value' => "estimated_value <= :over_estimated_value",
            'artifact_type' => "artifact_type = LIKE CONCAT('%', :artifact_type, '%')",
            'museum_id' => "museum_id = :museum_id",
            'condition' => "`condition` = :condition" // NOTE: backticks
        ];

        // the filters received are already validated,,,
        foreach ($filters as $k => $v) {
            if (!isset($sqlQueries[$k])) continue; //either a sort/order key, or an unknown key

            $sql .= " AND {$sqlQueries[$k]}";
            $conditions[$k] = $v;
        }

        $sql .= " ORDER BY {$sort_by} {$order}";

        return $this->paginate($sql, $conditions);
    }

    function getArtifactsById(int $artifact_id): array | False
    {
        $sql = "SELECT * FROM artifacts WHERE artifact_id = :artifact_id";

        return $this->fetchSingle($sql, ['artifact_id' => $artifact_id]);
    }
}
