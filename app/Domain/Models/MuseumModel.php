<?php

namespace App\Domain\Models;


class MuseumModel extends BaseModel
{
    //* READ
    public function getMuseums(array $filters): array
    {
        $filterConfig = [
            'museum_name' => [
                'column' => 'museum_name',
                'type' => 'contains'
            ],
            'museum_name_equals' => [
                'column' => 'museum_name',
                'type' => 'exact'
            ],
            'museum_name_startsWith' => [
                'column' => 'museum_name',
                'type' => 'startsWith'
            ],
            'museum_name_endsWith' => [
                'column' => 'museum_name',
                'type' => 'endsWith'
            ],
            'city' => [
                'column' => 'city',
                'type' => 'contains'
            ],
            'city_equals' => [
                'column' => 'city',
                'type' => 'exact'
            ],
            'city_startsWith' => [
                'column' => 'city',
                'type' => 'startsWith'
            ],
            'city_endsWith' => [
                'column' => 'city',
                'type' => 'endsWith'
            ],
            'museum_type' => [
                'column' => 'museum_type',
                'type' => 'contains'
            ],
            'museum_type_equals' => [
                'column' => 'museum_type',
                'type' => 'exact'
            ],
            'museum_type_startsWith' => [
                'column' => 'museum_type',
                'type' => 'startsWith'
            ],
            'museum_type_endsWith' => [
                'column' => 'museum_type',
                'type' => 'endsWith'
            ],
            'foundation_date_after' => [
                'column' => 'foundation_date',
                'type' => 'date_after'
            ],
            'foundation_date_before' => [
                'column' => 'foundation_date',
                'type' => 'date_before'
            ],
            'foundation_date_between' => [
                'column' => 'foundation_date',
                'type' => 'date_between'
            ],
            'has_website' => [
                'column' => 'website',
                'type' => 'presence'
            ]
        ];

        $sortableFields = [
            'museum_name',
            'city',
            'museum_type',
            'foundation_date'
        ];

        return $this->getCollection(
            $filters,
            'museum',
            $filterConfig,
            $sortableFields
        );
    }

    public function getMuseumById(int $museum_id): array | false
    {
        return $this->getCollectionById($museum_id, 'museum', 'museum_id');
    }

    public function getMuseumScientist(
        array $filters,
        int $museum_id
    ): array|false {

        // Verify that the parent museum exists.
        if ($this->getMuseumById($museum_id) === false) {
            return false;
        }

        $filterConfig = [
            'first_name' => [
                'column' => 'first_name',
                'type' => 'exact'
            ],
            'specialization' => [
                'column' => 'specialization',
                'type' => 'exact'
            ],
            'is_active' => [
                'column' => 'is_active',
                'type' => 'boolean'
            ],
            'min_experience' => [
                'column' => 'years_of_experience',
                'type' => 'min',
                'minAllowed' => 0,
                'maxAllowed' => 69
            ],
            'max_experience' => [
                'column' => 'years_of_experience',
                'type' => 'max',
                'minAllowed' => 1,
                'maxAllowed' => 70
            ],
            'has_degree' => [
                'column' => 'degree',
                'type' => 'enum',
                'allowed' => [
                    'BA',
                    'BSc',
                    'MA',
                    'MSc',
                    'PhD'
                ]
            ]
        ];

        $sortableFields = [
            'first_name',
            'last_name',
            'specialization',
            'degree',
            'years_of_experience'
        ];

        return $this->getSubCollection(
            $museum_id,
            'museum',
            'scientist',
            'museum_id',
            $filters,
            $filterConfig,
            $sortableFields
        );
    }

    public function getMuseumArtifact(
        array $filters,
        int $museum_id
    ): array|false {

        // Verify that the parent museum exists.
        if ($this->getMuseumById($museum_id) === false) {
            return false;
        }

        $filterConfig = [
            'artifact_name' => [
                'column' => 'artifact_name',
                'type' => 'contains'
            ],
            'artifact_type' => [
                'column' => 'artifact_type',
                'type' => 'exact'
            ],
            'condition' => [
                'column' => 'condition',
                'type' => 'exact'
            ]
        ];

        $sortableFields = [
            'artifact_name',
            'estimated_value',
            'condition'
        ];

        return $this->getSubCollection(
            $museum_id,
            'museum',
            'artifacts',
            'museum_id',
            $filters,
            $filterConfig,
            $sortableFields
        );
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
