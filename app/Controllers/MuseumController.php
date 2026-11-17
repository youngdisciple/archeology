<?php

namespace App\Controllers;

use App\Domain\Models\MuseumModel;
use App\Domain\Services\MuseumService;
use App\Helpers\WebServiceInvoker;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * MuseumController
 *
 * This class handles HTTP requests for the museum collection endpoints.
 *
 * It processes requests to retrieve museums, their associated scientist and artifact objects, and to create, update, or delete museum records. The class delegates data processing to the museum service and model, and generates JSON responses with appropriate HTTP status codes.
 */

class MuseumController extends BaseController
{
    /**
     * __construct
     *
     * Initialize the controller and its dependencies
     *
     * @param MuseumService $museumService
     * @param MuseumModel $museumModel
     * @param WebServiceInvoker $invoker
     */

    public function __construct(
        private MuseumService $museumService,
        private MuseumModel $museumModel,
        protected WebServiceInvoker $invoker
    ) {
        parent::__construct($museumModel, $museumService, $invoker);
    }

    //* GET /museums
    /**
     * handleGetMuseums
     *
     * Delegates the retrieval to the handler in the base controller and combines it with data from the MET API. If no objectID is specified for the MET, the default is object 437133
     *
     * @param Request $request Incoming HTTP request
     * @param Response $response HTTP response to be populated
     * @param array $args Route arguments, optionally the MET object ID
     * @return Response HTTP response produced by the base controller handler
     */

    public function handleGetMuseums(Request $request, Response $response, array $args): Response
    {
        // CALL the remote API here.
        $objectID = $args["objectID"] ?? "437133";
        return $this->handleGetCollection($request, $response, 'getMuseums',"https://collectionapi.metmuseum.org/public/collection/v1/objects/$objectID" );
    }

    /**
     * handleGetMuseumById
     *
     * Delegates the retrieval of one museum but its route identifier to the handler in the base collection.
     *
     * @param Request $request Incoming HTTP request
     * @param Response $response HTTP response to be populated
     * @param array $args Route parameters identifying the museum (museum_id)
     * @return Response HTTP response produced by the base controller handler
     */

    public function handleGetMuseumById(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetCollectionById($request, $response, $args, 'getMuseumById');
    }


    /**
     * handleGetMuseumScientist
     *
     * Delegates the retrieval of the sub-collection 'scientist' objects,related to a specific museum, to the base controller
     *
     * @param Request $request Incoming HTTP request
     * @param Response $response HTTP response to be populated
     * @param array $args Route parameters identifying the museum
     * @return Response HTTP response produced by the base controller handler
     */

    public function handleGetMuseumScientist(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetSubCollection($request, $response, $args, 'getMuseumScientist');
    }

    /**
     * handleGetMuseumArtifact
     *
     * Delegates the retrieval of the sub-collection 'artifacts' objects,related to a specific museum, to the base controller
     *
     * @param Request $request Incoming HTTP request
     * @param Response $response HTTP response to be populated
     * @param array $args Route parameters identifying the museum
     * @return Response HTTP response produced by the base controller handler
     */
    public function handleGetMuseumArtifact(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetSubCollection($request, $response, $args, 'getMuseumArtifact');
    }

    //* POST /museums
    /**
     * handleCreateMuseum
     *
     * Handles museum creation requests through the service and returns a JSON result (201 in the case of a success, 400 in the case of an error)
     *
     * @param Request $request Incoming HTTP request
     * @param Response $response HTTP response to be populated
     * @return Response HTTP JSON response rendered through the base controller
     */

    public function handleCreateMuseum(Request $request, Response $response): Response
    {
        // 1. Retrieve the representation of the new collection items to be created.
        $payload = $request->getParsedBody();

        // 2. Pass the list of new collection items to the service component to be validated and then inserted into the database.
        $result = $this->museumService->doCreateMuseums($payload);

        $responseData = [];
        $code = 201;

        // JSON response
        if ($result->isSuccess()) {
            $responseData = [
                "code" => 201,
                "status" => 'Created',
                "message" => 'The list of new museums was created successfully!'
            ];
        } else if ($result->isFailure()) {
            $code = 400;
            $responseData = [
                "code" => 400,
                "status" => 'Bad Request',
                "message" => 'An error is preventing the insertion of new items into the database.',
                "description" => $result->getErrors()
            ];
        }

        return $this->renderJson($response, $responseData, $code);
    }

    //* UPDATE /museums
    /**
     * handleUpdateMuseum
     *
     * Handles museum updating requests through the service and returns a JSON result (200 in the case of a success, 400 in the case of an error)
     *
     * @param Request $request Incoming HTTP request
     * @param Response $response HTTP response to be populated
     * @return Response HTTP JSON response rendered through the base controller
     */
    public function handleUpdateMuseum(Request $request, Response $response): Response
    {
        $payload = $request->getParsedBody();

        $result = $this->museumService->doUpdateMuseum($payload);

        $responseData = [];
        $code = 200;

        // JSON response
        if ($result->isSuccess()) {
            $responseData = [
                "code" => 200,
                "status" => 'OK',
                "message" => 'The museum was updated successfully!'
            ];
        } else if ($result->isFailure()) {
            $code = 400;
            $responseData = [
                "code" => 400,
                "status" => 'Bad Request',
                "message" => 'An error is preventing updating items in the database.',
                "description" => $result->getErrors()
            ];
        }

        return $this->renderJson($response, $responseData, $code);

    }

    //* DELETE /museums
    /**
     * handleDeleteMuseum
     *
     * Handles museum deletion requests through the service and returns a JSON result (201 in the case of a success, 400 in the case of an error)
     *
     * @param Request $request Incoming HTTP request
     * @param Response $response HTTP response to be populated
     * @return Response HTTP JSON response rendered through the base controller
     */
    public function handleDeleteMuseum(Request $request, Response $response): Response
    {
        $payload = $request->getParsedBody();

        $result = $this->museumService->doDeleteMuseum($payload);

        $responseData = [];
        $code = 200;

        // JSON response
        if ($result->isSuccess()) {
            $responseData = [
                "code" => 200,
                "status" => 'OK',
                "message" => 'The museum was deleted successfully!'
            ];
        } else if ($result->isFailure()) {
            $code = 400;
            $responseData = [
                "code" => 400,
                "status" => 'Bad Request',
                "message" => 'An error is preventing deleting items in the database.',
                "description" => $result->getErrors()
            ];
        }

        return $this->renderJson($response, $responseData, $code);

    }

}
