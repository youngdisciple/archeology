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
    ){}

    public function handleGetMuseums(Request $request, Response $response) : Response
    {
        $filters = $request->getQueryParams();

        $museums = $this->museumModel->getMuseums($filters);
        $data = json_encode($museums);
        $response->getBody()->write($data);

        return $response->withHeader(
            "Content-Type",
            "application/json"
        );
    }

    public function handleGetMuseumById(Request $request, Response $response, array $args) : Response
    {
        $museum_id = $args["museum_id"];

        $museum = $this->museumModel->getMuseumById($museum_id);

        $data = json_encode($museum);
        $response->getBody()->write($data);

        return $response->withHeader(
            "Content-Type",
            "application/json;charset=UTF-8"
        );
    }

    //* POST
        public function handleCreateMuseum(Request $request, Response $response) : Response
    {
        //* 1. Retrieve the representation of the new collection items to be created.
        $payload = $request->getParsedBody();
        //* 2. Pass the list of new collection items to the service component to be validated and then inserted into the database.
        $result = $this->museumService->doCreateMuseums($payload);

        $responseData = [];
        $code = 201;
        
        if ($result->isSuccess()){
            // We need to prepare a JSON response
            $responseData =[
                "code" => 201,
                "status"=> 'Success',
                "message" => 'The list of new museums was created successfully!'
            ];
        } else if ($result->isFailure()){
            $code = 400;
            $responseData =[
                "code" => 400,
                "status" => 'Error',
                "message" => 'An error is preventing the insertion of new items into the database.',
                "description" => $result->getErrors()
            ];
        }

        return $this->renderJson($response, $responseData, $code);

        dd($payload);
        echo "posting";
    }

    //* PUT

    //* DELETE
}
