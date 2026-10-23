<?php

namespace App\Domain\Models;

use App\Domain\Models\BaseModel;

class ScientistsModel extends BaseModel
{
    public function getScientists(array $filters): array
    {
        $placeholder_values = [];
        $sql = "SELECT * FROM scientist WHERE 1=1";
        $scientist_name = $filters["name"] ?? '';
        if (!empty($scientist_name)) {
            $sql .= " AND name LIKE CONCAT('%', :scientist_name, '%')";
            $placeholder_values["scientist_name"] = $scientist_name;
        }
        return $this->paginate($sql, $placeholder_values);
    }

    public function getScientistById(int $scientist_id): array | false
    {
        $sql = "SELECT * FROM scientist WHERE scientist_id = :scientist_id";

        return $this->fetchSingle(
            $sql,
            ["scientist_id" => $scientist_id]
        );
    }

    public function createScientist(array $scientist)
    {
        return $this->insert("scientist", $scientist);
    }
}
