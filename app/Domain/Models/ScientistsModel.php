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

        return $this->getCollection($filters, 'scientist', $filterConfig, $sortableFields);
    }

    /**
     * Returns one scientist based off the provided ID
     * @param int $scientist_id The user provided ID for the scientist they want
     * @return array|false returns the scientist that the user requested or false if the scientist does not exist
     */
    public function getScientistById(int $scientist_id): array | false
    {
        return $this->getCollectionById($scientist_id, 'scientist', 'scientist_id');
    }

    public function getScientistExcavation(
        array $filters,
        int $scientist_id
    ): array|false {

        // Verify that the parent museum exists.
        if ($this->getScientistById($scientist_id) === false) {
            return false;
        }

        $filterConfig = [
            'project_name' => [
                'column' => 'project_name',
                'type' => 'contains'
            ],
            'start_date_after' => [
                'column' => 'start_date',
                'type' => 'date_after'
            ],
            'start_date_before' => [
                'column' => 'start_date',
                'type' => 'date_before'
            ],
            'start_date_between' => [
                'column' => 'start_date',
                'type' => 'date_between'
            ],
            'min_budget' => [
                'column' => 'budget',
                'type' => 'min',
                'minAllowed' => 0,
                'maxAllowed' => 100000000
            ],
            'max_budget' => [
                'column' => 'budget',
                'type' => 'max',
                'minAllowed' => 1,
                'maxAllowed' => 99999999
            ]
        ];

        $sortableFields = [
            'project_name',
            'start_date',
            'end_date',
            'budget',
            'area_excavated_m2',
            'team_size'
        ];

        return $this->getSubCollection(
            $scientist_id,
            'scientist',
            'excavations',
            'scientist_id',
            $filters,
            $filterConfig,
            $sortableFields
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
