<?php

namespace App\Controllers;

use App\Domain\Models\ArtifactsModel;
use App\Domain\Services\ArtifactsService;
use App\Helpers\WebServiceInvoker;
use App\Validation\ValidationHelper;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class ArtifactsController extends BaseController
{
    public function __construct( private ArtifactsModel $artifactsModel, private ArtifactsService $artifactsService ) {}


    /**
    * Get a collection of artifacts
    *
    * @param Request $request
    * @param Response $response
    * @return Response
    */
    public function index(Request $request, Response $response): Response {
        // filters and sorting
        $params = $request->getQueryParams();

        if (!empty($params)) {
            $this->artifactsModel->setPaginationOptions(
                $params['page'] ?? null,
                $params['page_size'] ?? null
            );

            $rules = [
                "artifact_name" => fn() => true, // No rules, free pass
                "artifact_name_2" => fn() => true, // FOR API
                "under_estimated_value" => fn($v) => preg_match(NON_NEGATIVE_DECIMAL_NUMBER, $v),
                "over_estimated_value" => fn($v) => preg_match(NON_NEGATIVE_DECIMAL_NUMBER, $v),
                "artifact_type" => fn() => true,
                "museum_id" => fn($v) => preg_match(POSITIVE_WHOLE_NUMBER, $v),
                "condition" => fn($v) => in_array($v, ["mint", "excellent", "good", "fair", "poor"]),
                "sort_by" => fn($v) => in_array($v, ['artifact_id', 'artifact_name', 'estimated_value', 'condition']),
                'order' => fn($v) => in_array($v, ['asc', 'desc'])
            ];

            $params = ValidationHelper::validateParams($request, $rules);
        }

        $artifacts = $this->artifactsModel->getArtifacts($params);

        $artifactName2 = $params['artifact_name_2'] ?? null;
        if (!empty($artifactName2)) {
            $artifacts['related_data_api'] = $this->getApiArtifacts($artifactName2);
        }

        return $this->renderJson($response, $artifacts);
    }

    /**
    * Get a an artifact singleton from id
    *
    * @param Request $request
    * @param Response $response
    * @param array $args
    * @return Response
    */
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

    public function createArtifacts(Request $request, Response $response, array $args): Response {
        $payload = $request->getParsedBody();

        if (empty($payload)) {
            throw new HttpBadRequestException(
                $request,
                'The payload must contain at least 1 artifact.'
            );
        }

        $result = $this->artifactsService->doCreateArtifacts($payload);

        $responseData = [];
        $code = 201;

        // JSON response
        if ($result->isSuccess()) {
            $responseData = [
                "code" => 201,
                "status" => 'Success',
                "message" => 'The list of new artifact(s) was created successfully!'
            ];
        } else{
            $code = 400;
            $responseData = [
                "code" => 400,
                "status" => 'Error',
                "message" => 'An error is preventing the insertion of new items into the database.',
                "description" => $result->getErrors()
            ];
        }

        return $this->renderJson($response, $responseData, $code);
    }

    public function updateArtifacts(Request $request, Response $response, array $args): Response {
        $payload = $request->getParsedBody();

        if (empty($payload)) {
            throw new HttpBadRequestException(
                $request,
                'The payload must contain at least 1 artifact.'
            );
        }

        $result = $this->artifactsService->doUpdateArtifacts($payload);

        $responseData = [];
        $code = 200;

        // JSON response
        if ($result->isSuccess()) {
            $responseData = [
                "code" => 200,
                "status" => 'OK',
                "message" => 'The list of new artifact(s) was updated successfully',
                'description' => $result->getData()
            ];
        } else{
            $code = 400;
            $responseData = [
                "code" => 400,
                "status" => 'Error',
                "message" => 'An error is preventing the database to update the artifacts',
                "description" => $result->getErrors()
            ];
        }

        return $this->renderJson($response, $responseData, $code);
    }

    public function deleteArtifacts(Request $request, Response $response, array $args): Response {
        $payload = $request->getParsedBody();

        if (empty($payload)) {
            throw new HttpBadRequestException(
                $request,
                'The payload must contain at least 1 artifact.'
            );
        }

        $result = $this->artifactsService->doDeleteArtifacts($payload);

        $responseData = [];
        $code = 200;

        // JSON response
        if ($result->isSuccess()) {
            $responseData = [
                "code" => 200,
                "status" => 'OK',
                "message" => 'The artifacts were deleted successfully',
                'description' => $result->getData()
            ];
        } else{
            $code = 400;
            $responseData = [
                "code" => 400,
                "status" => 'Error',
                "message" => 'An error is preventing the database to delete the artifacts',
                "description" => $result->getErrors()
            ];
        }

        return $this->renderJson($response, $responseData, $code);
    }

    // FIX : polish composite resource
    /**
     * Fetches artifacts from the external API
     * @param string $q
     * @return mixed
     */
    private function getApiArtifacts(string $q) : mixed
    {
        $data = WebServiceInvoker::invokeUri("https://api.artic.edu/api/v1/artworks?q=$q&limit=1");
        return $data;
    }
}
