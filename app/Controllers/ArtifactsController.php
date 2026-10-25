<?php

namespace App\Controllers;

use App\Domain\Models\ArtifactsModel;
use App\Validation\ValidationHelper;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class ArtifactsController extends BaseController
{
    public function __construct( private ArtifactsModel $artifactsModel ) {}


    // Returns a collection of artifacts
    public function index(Request $request, Response $response): Response {
        $filters = $request->getQueryParams();

        // TODO: sorting

        if (!empty($filters)) {
            $this->artifactsModel->setPaginationOptions(
                $filters['page'] ?? null,
                $filters['page_size'] ?? null
            );

            $rules = [
                "artifact_name" => fn($v) => true, // No rules, free pass
                "under_estimated_value" => fn($v) => preg_match(NON_NEGATIVE_DECIMAL_NUMBER, $v),
                "over_estimated_value" => fn($v) => preg_match(NON_NEGATIVE_DECIMAL_NUMBER, $v),
                "artifact_type" => fn($v) => true,
                "museum_id" => fn($v) => preg_match(POSITIVE_WHOLE_NUMBER, $v),
                "condition" => fn($v) => in_array($v, ["Mint", "Excellent", "Good", "Fair", "Poor"]),
            ];

            $filters = ValidationHelper::validateFilters($request, $rules);
        }

        $artifacts = $this->artifactsModel->getArtifacts($filters);

        return $this->renderJson($response, $artifacts);
    }

    // Returns an artifact singleton (from id)
    public function show(Request $request, Response $response, array $args): Response {
        $artifact_id = $args['artifact_id'];

        if (!preg_match(POSITIVE_WHOLE_NUMBER, $artifact_id)) {
            throw new HttpBadRequestException(
                $request,
                "The provided artifact id ({$artifact_id}) is invalid (Must be a positive whole number)."
            );
        }

        $artifact = $this->artifactsModel->getArtifactsById($artifact_id);

        if (!$artifact) {
            throw new HttpNotFoundException(
                $request,
                "There was no matching record for artifact_id = ({$artifact_id})"
            );
        }

        return $this->renderJson($response, $artifact);
    }
}
