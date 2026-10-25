<?php

namespace App\Controllers;

use App\Domain\Models\MuseumModel;
use App\Domain\Services\MuseumService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MuseumController extends BaseController
{

    public function __construct(
        private MuseumService $museumService,
        private MuseumModel $museumModel
    ) {
        parent::__construct($museumModel);
    }

    public function handleGetMuseums(Request $request, Response $response): Response
    {
        return $this->handleGetCollection($request, $response, 'getMuseums');
    }

    public function handleGetMuseumById(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetCollectionById($request, $response, $args, 'getMuseumById');
    }

    public function handleGetMuseumScientist(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetSubCollection($request, $response, $args, 'getMuseumScientist');
    }

    public function handleGetMuseumArtifact(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetSubCollection($request, $response, $args, 'getMuseumArtifact');
    }

    //* POST
    public function handleCreateMuseum(Request $request, Response $response): Response
    {
        //* 1. Retrieve the representation of the new collection items to be created.
        $payload = $request->getParsedBody();

        //* 2. Pass the list of new collection items to the service component to be validated and then inserted into the database.
        $result = $this->museumService->doCreateMuseums($payload);

        $responseData = [];
        $code = 201;

        // JSON response
        if ($result->isSuccess()) {
            $responseData = [
                "code" => 201,
                "status" => 'Success',
                "message" => 'The list of new museums was created successfully!'
            ];
        } else if ($result->isFailure()) {
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
}
