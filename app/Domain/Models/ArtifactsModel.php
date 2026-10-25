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

        // artifact_name
        $artifact_name = $filters['artifact_name'] ?? '';
        if ( !empty($name) ){
            $sql .= " AND artifact_name LIKE CONCAT('%', :artifact_name, '%')";
            $conditions['artifact_name'] = $artifact_name;
        }

        // over_estimated_value
        $over_estimated_value = $filters['over_estimated_value'] ?? '';
        if ( !empty($over_estimated_value) ){
            $sql .= " AND estimated_value >= :over_estimated_value";
            $conditions['over_estimated_value'] = $over_estimated_value;
        }

        // below_estimated_value
        $below_estimated_value = $filters['below_estimated_value'] ?? '';
        if ( !empty($below_estimated_value) ){
            $sql .= " AND estimated_value <= :below_estimated_value";
            $conditions['below_estimated_value'] = $below_estimated_value;
        }

        // artifact_type
        $artifact_type = $filters['artifact_type'] ?? '';
        if ( !empty($artifact_type) ) {
            $sql .= " AND artifact_type <= :artifact_type";
            $conditions['artifact_type'] = $artifact_type;
        }

        // museum_id
        $museum_id = $filter['museum_id'] ?? '';
        if ( !empty($museum_id) ) {
            $sql .= " AND museum_id <= :museum_id";
            $conditions['museum_id'] = $museum_id;
        }

        // condition
        $condition = $filters['condition'];
        if ( !empty($condition) ) {
            $sql .= " AND condition <= :condition";
            $conditions['condition'] = $condition;
        }

        // WARNING: conditions not condition
        return $this->paginate($sql, $conditions);
    }

    // TODO:
    function getArtifactsById(int $artifact_id): array | False
    {
        $sql = "SELECT * FROM artifacts WHERE id = :id";

        return $this->fetchSingle($sql, ['id' => $artifact_id]);
    }
}
