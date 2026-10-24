<?php

namespace App\Domain\Models;


class MuseumModel extends BaseModel
{
    //* READ
    public function getMuseums(array $filters): array
    {
        return $this->getCollection($filters, 'museum', 'museum_name');
    }

    public function getMuseumById(int $museum_id): array | false
    {
        return $this->getCollectionById($museum_id, 'museum', 'museum_id');
    }

    public function getMuseumScientist(array $filters, int $museum_id): array | false
    {
        return $this->getSubCollection($museum_id, 'museum', 'scientist', 'museum_id');
    }

    public function getMuseumArtifact(array $filters, int $museum_id): array | false
    {
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
