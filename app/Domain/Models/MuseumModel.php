<?php

namespace App\Domain\Models;


class MuseumModel extends BaseModel
{
    public function getMuseums(array $filters): array
    {
        $placeholder_values = [];
        $sql = "SELECT * FROM museum WHERE 1=1 ";
        $museum_name = $filters["name"] ?? '';
        if (!empty($museum_name)) {
            $sql .= " AND name LIKE CONCAT ('%', :museum_name, '%')";
            $placeholder_values["museum_name"] = $museum_name;
        }
        return $this->paginate($sql, $placeholder_values);
    }

    public function getMuseumById(int $museum_id): array | false
    {

        $sql = "SELECT * FROM museum WHERE  museum_id = :museum_id";

        return $this->fetchSingle($sql, ["museum_id" => $museum_id]);
    }

    public function createMuseum(array $museum)
    {
        return $this->insert(
            "museum",
            $museum
        );
    }
}
