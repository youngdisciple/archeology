<?php

namespace App\Domain\Models;

use App\Helpers\WebServiceInvoker;

/**
 * MuseumModel
 *
 * This class manages data retrieval from the database.
 *
 * It provides operations for creating, retrieving, updating, and deleting museum records. It supports filtering and sorting the collection, as well as retrieving its sub-collections (scientists and artifacts). The model also fetches object data from the Metropolitan Museum of Art API to combine with the database as a composite resource.
 */

class MuseumModel extends BaseModel
{
    //* READ
    /**
     * getMuseums
     *
     * Retrieves museums using the supported filtering and sorting options, and adds an "objects" entry containing data fetched from the MET API
     *
     * @param array $filters query parameters for filtering, sorting, and the external object ID
     * @return array Museum collections + MET objects
     */

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

        $data  = $this->getCollection(
            $filters,
            'museum',
            $filterConfig,
            $sortableFields
        );
        $data["objects"] = $this->getListOfObjects($filters);

        return $data;
    }

    /**
     * getListOfObjects
     *
     * Retrieves one object from the MET API based on its ID (the default is 437133).
     *
     * @param mixed $filters filters optionally containing objectID
     * @return mixed Response returned by the web service invoker
     */

    public function getListOfObjects($filters): mixed
    {
        //TODO: extract the filter that identifies the object id you want to pull?
        $objectID = $filters["objectID"] ?? "437133";
        $response = [];
        try {
            $invoker = new WebServiceInvoker();
            $uri = "https://collectionapi.metmuseum.org/public/collection/v1/objects/$objectID";
            // echo $uri;
            $response = $invoker->invokeUri($uri);
        } catch (\Throwable $th) {
            throw $th;
        }

        return $response;
    }

    /**
     * getMuseumById
     *
     * Retrieves a single museum by its ID through the inherited base model function
     *
     * @param int $museum_id Identifier or the museum to be retrieved
     * @return array|false
     */

    public function getMuseumById(int $museum_id): array | false
    {
        return $this->getCollectionById($museum_id, 'museum', 'museum_id');
    }

    /**
     * getMuseumScientist
     *
     * Retrieves scientist objects associated to a museum through the inherited base model function. Supports filtering and sorting on scientist objects.
     *
     * @param array $filters Scientist filtering and sorting options
     * @param int $museum_id Identifier of the parent museum
     * @return array|false
     */
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
            'name',
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

    /**
     * getMuseumArtifact
     *
     * Retrieves artifact objects associated to a museum through the inherited base model function. Supports filtering and sorting on artifact objects.
     *
     * @param array $filters Artifact filtering and sorting options
     * @param int $museum_id Identifier of the parent museum
     * @return array|false
     */
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
    /**
     * createMuseum
     *
     * Inserts a museum record into the database through the inherited base model function
     *
     * @param array $museum Museum values to insert
     * @return mixed
     */
    public function createMuseum(array $museum) : mixed
    {
        return $this->insert(
            "museum",
            $museum
        );
    }

    //* UPDATE
    /**
     * updateMuseum
     *
     * Update a museum record in  the database by its ID, through the inherited base model function
     *
     * @param array $museum Museum values to update
     * @return mixed
     */

    public function updateMuseums(array $museum, int $museum_id) : mixed
    {
        return $this->update(
            "museum",
            $museum,
            ["museum_id" => $museum_id]
        );
    }

    //* DELETE
    /**
     * deleteMuseum
     *
     * Deletes a museum record in the database bby its ID, through the inherited base model function
     *
     * @param int $museum_id  Identifier of the museum to delete
     * @param int $limit Maximum number of records to delete, defaults to 1
     * @return mixed
     */

    public function deleteMuseums(int $museum_id, int $limit = 1) : mixed
    {
        return $this->delete(
            "museum",
            ["museum_id" => $museum_id],
            $limit
        );
    }
}
