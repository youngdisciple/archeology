<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Models\BaseModel;
use App\Exceptions\ConflictException;
use App\Helpers\Core\Result as Result;
use Frostybee\Valicomb\Validator;
use PDOException;

/**
 * Base service class for all services.
 *
 * This class provides a base implementation for all services.
 * It is intended to be extended by specific service classes.
 */
abstract class BaseService
{
    //*NOTES: This class is not used yet. It is a placeholder for future services.
    //*NOTES: Here, you can define common methods for all services such as logging, validation, etc.

    /**
     * @param BaseModel $base_model a dependency injection of the base model is needed
     */
    protected function __construct(protected BaseModel $base_model) {}

    /**
     * A reusable function that is applicable to all children of the `BaseService` and handles insertion and validation if a collection into the database
     * @param array $new_collection the collection array
     * @param array $validation_rules the associative array of the validation rules see documentation at https://github.com/frostybee/valicomb/tree/main/docs/src/content/docs/rules
     * @param string $model_func string representation that creates from any child of `BaseModel`
     * @return Result
     */
    protected function doCreateCollection(array $new_collection, array $validation_rules, string $model_func): Result
    {
        $error_data = [];
        $created_item_ids = [];
        if (empty($new_collection)) {
            return Result::failure("The request body must be a non-empty list of items.", []);
        }
        foreach ($new_collection as $collection_entry) {
            if (!is_array($collection_entry)) {
                $error_data[] = ['item' => ['Each new item must be an object']];
                continue;
            }

            $collection_rules = new Validator($collection_entry);
            $collection_rules->forFields($validation_rules);
            if (!$collection_rules->validate()) {
                $error_data[] = $collection_rules->getErrors();
            }
        }
        if (!empty($error_data)) {
            return Result::failure("An error occurred while trying to create the collection!", $error_data);
        } else {
            foreach ($new_collection as $collection) {
                $last_inserted_id = $this->base_model->$model_func($collection);
                $created_item_ids[] = $last_inserted_id;
            }
        }
        return Result::success("New collection got added successfully!", $created_item_ids);
    }

    /**
     * A reusable function that is applicable to all children of the `BaseService` and handles updates and validation if a collection into the database
     * @param array $updated_collection the collection array
     * @param array $validation_rules the associative array of the validation rules see documentation at https://github.com/frostybee/valicomb/tree/main/docs/src/content/docs/rules
     * @param string $id_field the id name (primary key) of the collection you want to update
     * @param string $model_func string representation that creates from any child of `BaseModel`
     * @return Result
     */
    protected function doUpdateCollection(array $updated_collection, array $validation_rules, string $id_field, string $model_func): Result
    {
        $error_data = [];
        if (empty($updated_collection)) {
            return Result::failure("The request body must be a non-empty list of items.", []);
        }
        foreach ($updated_collection as $collection_entry) {
            $collection_rules = new Validator($collection_entry);
            $collection_rules->forFields(array_merge([$id_field => [
                'required',
                'integer',
                ['min', 1]
            ]], $validation_rules));
            if (!$collection_rules->validate()) {
                $error_data[] = $collection_rules->getErrors();
            }
        }
        if (!empty($error_data)) {
            return Result::failure("An error occurred while trying to update the collection!", $error_data);
        }
        foreach ($updated_collection as $collection) {
            $collection_id = $collection[$id_field];
            unset($collection[$id_field]);
            //try {
            $this->base_model->$model_func($collection, (int)$collection_id);
            /* } catch (PDOException $e) {
                throw new ConflictException();
            }
            */
        }
        return Result::success("Collection resource was updated successfully!");
    }

    /**
     * A reusable function that is applicable to all children of the `BaseService` and handles deleting and validation if a collection into the database
     * @param array $delete_collection the collection array
     * @param string $id_field the id name (primary key) of the collection you want to delete
     * @param string $model_func string representation that creates from any child of `BaseModel`
     * @return Result
     */
    protected function doDeleteCollection(array $delete_collection, string $id_field, string $model_func): Result
    {
        $error_data = [];
        if (empty($delete_collection)) {
            return Result::failure("The request body must be a non-empty list of items.", []);
        }
        foreach ($delete_collection as $collection_entry) {
            $collection_rules = new Validator($collection_entry);
            $collection_rules->forFields([$id_field => [
                'required',
                'integer',
                ['min', 1]
            ]]);
            if (!$collection_rules->validate()) {
                $error_data[] = $collection_rules->getErrors();
            }
        }
        if (!empty($error_data)) {
            return Result::failure("An error occurred while trying to delete from the collection!", $error_data);
        }
        foreach ($delete_collection as $collection) {
            $collection_id = $collection[$id_field];
            $this->base_model->$model_func((int)$collection_id);
        }
        return Result::success("Collection resource deleted successfully!");
    }
}
