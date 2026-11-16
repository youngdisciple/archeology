<?php

namespace App\Domain\Services;
use App\Domain\Models\ArtifactsModel;
use App\Helpers\Core\Result;
use Frostybee\Valicomb\Validator;

class ArtifactsService extends BaseService
{
    public function __construct(private ArtifactsModel $artifactsModel) {}

    private function validateArtifactPayload(array $payload, bool $requireId = false) {
        $errors = [];
        // reusable >>>> standard
        $v = new Validator([]);
        $v->strict();

        // NOTE: artifact_id is listed for update and delete. artifact insertion
        // does not have that field hence why it isn't 'required'
        $v->forFields([
            'artifact_id' => $requireId ? ['required', 'integer'] : [],
            'artifact_name' => ['required', ['lengthBetween', 1, 150]],
            'estimated_value' => [['between', 0, 100000000]],
            'artifact_type' => [['lengthMax', 50]],
            'museum_id' => ['integer', ['between', 0, 10000]],
            'condition' => [['in', ['mint', 'excellent', 'good', 'fair', 'poor']]],
            'material' => [['lengthMax', 100]],
            'weight_in_g' => [['between', 0, 100000000]],
            'height_in_cm' => [['between', 0, 1000000]]
        ]);

        foreach ($payload as $artifact) {
            // BUG: check structure of the data first (e.g., integer fails and gives
            // server error.)
            $tmpV = $v->withData($artifact);

            if (!$tmpV->validate()) {
                array_push($errors, $tmpV->errors());
            }
        }

        return $errors;
    }

    public function doCreateArtifacts(array $artifacts): Result {
        $errors = $this->validateArtifactPayload($artifacts);

        if (count($errors) === 0) {
            // success
            $last_inserted_ids = $this->artifactsModel->createArtifacts($artifacts);

            return Result::success('The new artifacts were created successfully', $last_inserted_ids);
        } else {
            return Result::failure('There was an error while inserting the artifacts', $errors);
        }
    }

    //TODO: Add two new public methods: One for the update operation and one for delete operation.
    public function doUpdateArtifacts(array $artifacts): Result {
        $errors = $this->validateArtifactPayload($artifacts, true);

        if (count($errors) === 0) {
            $affectedRows = $this->artifactsModel->updateArtifacts($artifacts);

            return Result::success('The artifacts were updated successfully', $affectedRows);
        } else {
            return Result::failure('There was an error while updating the artifacts', $errors);
        }
    }

    public function doDeleteArtifacts(array $artifacts_ids): Result {
        $errors = [];

        foreach ($artifacts_ids as $id) {
            $v = new Validator(['artifact_id' => $id]);
            $v->strict();
            $v->rule('required', 'artifact_id');
            $v->rule('integer', 'artifact_id');

            if (!$v->validate()) {
                array_push($errors, $v->errors());
            }
        }

        if (count($errors) === 0) {
            $affectedRows = $this->artifactsModel->deleteArtifacts($artifacts_ids);

            return Result::success('The artifacts were deleted successfully', $affectedRows);
        } else {
            return Result::failure('There was an error while deleting the artifacts', $errors);
        }
    }
}
