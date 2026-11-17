<?php

namespace App\Domain\Models;

use App\Domain\Models\BaseModel;

class ScientistsModel extends BaseModel
{
    /**
     * Fetches the list of all scientists.
     *
     * @param array $filters The array that features all filters for the scientists table
     *
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
     * Returns one scientist based off the provided ID.
     *
     * @param int $scientist_id The user provided ID for the scientist they want
     *
     * @return array|false returns the scientist that the user requested or false if the scientist does not exist
     */
    public function getScientistById(int $scientist_id): array | false
    {
        return $this->getCollectionById($scientist_id, 'scientist', 'scientist_id');
    }

    /**
     * Fetches the list of excavations that a scientist was involved in.
     *
     * @param array $filters The array that features all filters for the excavations sub-collection
     * @param int $scientist_id The ID of the parent scientist
     *
     * @return array|false the filtered list of excavations or false if the scientist does not exist
     */
    public function getScientistExcavation(
        array $filters,
        int $scientist_id
    ): array|false {

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
     * Creates a scientist based on user provided data.
     *
     * @param array $scientist The array that contains all of the data for the new scientist
     *
     * @return mixed the ID of the newly inserted scientist
     */
    public function createScientist(array $scientist)
    {
        return $this->insert("scientist", $scientist);
    }

    /**
     * Updates an existing scientist.
     *
     * @param array $scientist The array that contains the new data for the scientist
     * @param int $scientist_id The ID of the scientist to update
     *
     * @return int the number of rows that were affected, 0 if no scientist was updated
     */
    public function updateScientist(array $scientist, int $scientist_id)
    {
        return $this->update('scientist', $scientist, ['scientist_id' => $scientist_id]);
    }

    /**
     * Deletes a scientist.
     *
     * @param int $scientist_id The ID of the scientist to delete
     *
     * @return int the number of rows that were deleted, 0 if no scientist exists with that ID
     */
    public function deleteScientist(int $scientist_id)
    {
        return $this->delete('scientist', ['scientist_id' => $scientist_id]);
    }
}
