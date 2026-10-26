<?php

namespace App\Controllers;

use App\Domain\Services\ScientistsService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ScientistsController extends BaseController
{
    public function __construct(private ScientistsService $scientists_service) {}

    public function handleGetScientists(Request $request, Response $response): Response
    {
        return $this->handleGetCollection($request, $response, 'getScientists');
    }

    public function handleGetScientistById(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetCollectionById($request, $response, $args, 'getScientistById');
    }

    public function handleGetScientistExcavation(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetSubCollection($request, $response, $args, 'getScientistExcavation');
    }

    //* POST /VENDORS
    function handleCreateScientists(Request $request, Response $response): Response
    {
        $payload = $request->getParsedBody();

        $result = $this->scientists_service->doCreateScientists($payload);

        $response_data = [];
        $code = 201;
        if ($result->isSuccess()) {
            $response_data = [
                "code" => 201,
                "status" => "success",
                "message" => "The list of new scientists was successfully created/processed",
                "items_ids" => $result->getData(),
            ];
        }
        if ($result->isFailure()) {
            $code = 400;
            $response_data = [
                "code" => 400,
                "status" => "error",
                "message" => "Something prevented the insertion of new scientists",
                "details" => $result->getErrors(),
            ];
        }

        return $this->renderJson($response, $response_data, $code);
    }
}
