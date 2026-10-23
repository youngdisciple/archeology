<?php

namespace App\Controllers;

use App\Domain\Models\ScientistsModel;
use App\Domain\Services\ScientistsService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class ScientistsController extends BaseController
{
public function __construct(private ScientistsModel $scientists_model, private ScientistsService $scientists_service) {}

    public function handleGetScientists(Request $request, Response $response): Response
    {
        $filters = $request->getQueryParams();
        $this->scientists_model->setPaginationOptions($filters["page"], $filters["page_size"]);
        $Scientists = $this->scientists_model->getScientists($filters);
        return $this->renderJson($response, $Scientists);
    }

    public function handleGetScientistById(Request $request, Response $response, array $args): Response
    {
        $scientist_id = $args["scientist_id"];
        $pattern = "/^\d{1,9}$/";
        if (preg_match($pattern, $scientist_id) === 0) {
            //* Invalid ID detected: We still need to return a VALID HTTP error response

            throw new HttpBadRequestException($request, "The received scientist ID $scientist_id was no bueno! It must be a positive integer");

            return $this->renderJson($response, $payload, 400);
        }

        $scientist = $this->scientists_model->getScientistById($scientist_id);
        if ($scientist === false) {
            throw new HttpNotFoundException($request, "There was no matching record for scientist id: $scientist_id ");
        }
        return $this->renderJson($response, $scientist);
    }

    //* POST /VENDORS
    function handleCreateScientists( Request $request, Response $response) : Response {
        $payload = $request->getParsedBody();

        $result = $this->scientists_service->doCreateScientists($payload);

        $response_data = [];
        $code = 201;
        if ($result->isSuccess()) {
            $response_data = [
                "code" => 201,
                "status" => "success",
                "message" => "The list of new scientists was successfully created/processed"
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
