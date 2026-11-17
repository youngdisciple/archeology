<?php

namespace App\Domain\Services;

use App\Domain\Models\ScientistsModel;
use App\Helpers\Core\Result;

class ScientistsService extends BaseService
{
    /**
     * A constant array for Valicomb to validate inputs for the /scientist resource
     *
     * @var array
     */
    private const SCIENTIST_RULES = [
        'first_name' => [
            'required',
            'ascii',
            ['lengthBetween', 1, 50]
        ],
        'last_name' => [
            'required',
            'ascii',
            ['lengthBetween', 1, 50]
        ],
        'degree' => [
            'required',
            ['in', ['BA', 'BSc', 'MA', 'MSc', 'PhD']]
        ],
        'specialization' => [
            'required',
            'ascii',
            ['lengthBetween', 1, 100]
        ],
        'museum_id' => [
            'optional',
            'positive',
            'integer'
        ],
        'years_of_experience' => [
            'required',
            'integer',
            'positive',
            ['min', 1],
            ['max', 80]
        ],
        'is_active' => [
            'required',
            ['in', [true, false, 1, 0]]
        ],
        'biography' => [
            'optional',
            'ascii',
            ['lengthBetween', 1, 5000]
        ]
    ];
    public function __construct(private ScientistsModel $scientistsModel)
    {
        parent::__construct($scientistsModel);
    }

    /**
     * Validates and creates a list of new scientists.
     *
     * @param array $new_scientists the list of scientists to create, taken from the request body
     *
     * @return Result a success Result containing the IDs of the new scientists, or a failure Result containing the validation errors
     */
    public function doCreateScientists(array $new_scientists): Result
    {
        return $this->doCreateCollection($new_scientists, self::SCIENTIST_RULES, 'createScientist');
    }

    /**
     * Validates and updates a list of existing scientists.
     *
     * @param array $updated_scientists the list of scientists to update, each item must include its scientist_id
     *
     * @throws \OutOfBoundsException if no scientist exists with one of the provided IDs
     *
     * @return Result a success Result if every scientist was updated, or a failure Result containing the validation errors
     */
    public function doUpdateScientists(array $updated_scientists): Result
    {
        return $this->doUpdateCollection($updated_scientists, self::SCIENTIST_RULES, 'scientist_id', 'updateScientist');
    }

    /**
     * Validates and deletes a list of scientists.
     *
     * @param array $deleted_scientists the list of items to delete, each item must include its scientist_id
     *
     * @throws \OutOfBoundsException if no scientist exists with one of the provided IDs
     *
     * @return Result a success Result if every scientist was deleted, or a failure Result containing the validation errors
     */
    public function doDeleteScientists(array $deleted_scientists): Result
    {
        return $this->doDeleteCollection($deleted_scientists, 'scientist_id', 'deleteScientist');
    }
}
