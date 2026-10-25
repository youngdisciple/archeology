<?php

namespace App\Domain\Models;


class MuseumModel extends BaseModel
{
    //* READ
    public function getMuseums(array $filters): array
    {
        return $this->getCollection($filters, 'museum', 'museum_name', [
            'museum_name',
            'city',
            'museum_type',
            'foundation_date'
        ],
        // String filters
        [
            'city',
            'museum_type'
        ],
        // Date filters
        [
            'foundation_date'
        ],
        // Boolean filters
        [
            'has_website' => 'website'
        ]);
    }

    public function getMuseumById(int $museum_id): array | false
    {
        return $this->getCollectionById($museum_id, 'museum', 'museum_id');
    }

    public function getMuseumScientist(array $filters, int $museum_id): array | false
    {
        // Verify that the parent museum exists
        if ($this->getMuseumById($museum_id) === false) {
            return false;
        }

        return $this->getSubCollection($museum_id, 'museum', 'scientist', 'museum_id');
    }

    public function getMuseumArtifact(array $filters, int $museum_id): array | false
    {
        // Verify that the parent museum exists
        if ($this->getMuseumById($museum_id) === false) {
            return false;
        }
        return $this->getSubCollection($museum_id, 'museum', 'artifacts', 'museum_id');
    }

    //* CREATE
    public function createMuseum(array $museum)
    {
        return $this->insert(
            "museum",
            $museum
        );
    }
}
