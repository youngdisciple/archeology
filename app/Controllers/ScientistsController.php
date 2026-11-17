<?php

namespace App\Controllers;

use App\Domain\Models\ScientistsModel;
use App\Domain\Services\ScientistsService;
use App\Helpers\WebServiceInvoker;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ScientistsController extends BaseController
{
    public function __construct(private ScientistsModel $scientists_model, private ScientistsService $scientists_service, protected WebServiceInvoker $web_service_invoker)
    {
        parent::__construct($scientists_model, $scientists_service, $web_service_invoker);
    }

    /**
     * Handles GET /scientists.
     *
     * Returns the filtered, sorted and paginated list of scientists.
     *
     * @param Request $request the incoming HTTP request, its query parameters are used as filters
     * @param Response $response the HTTP response to write to
     *
     * @return Response the JSON encoded list of scientists
     */
    public function handleGetScientists(Request $request, Response $response): Response
    {
        return $this->handleGetCollection($request, $response, 'getScientists', 'placeholder');
    }

    /**
     * Handles GET /scientists/{scientist_id}.
     *
     * Returns a single scientist based on the ID provided in the URI.
     *
     * @param Request $request the incoming HTTP request
     * @param Response $response the HTTP response to write to
     * @param array $args the URI arguments, contains the scientist ID
     *
     * @return Response the JSON encoded scientist
     */
    public function handleGetScientistById(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetCollectionById($request, $response, $args, 'getScientistById');
    }

    /**
     * Handles GET /scientists/{scientist_id}/excavations.
     *
     * Returns the list of excavations that the specified scientist was involved in.
     *
     * @param Request $request the incoming HTTP request, its query parameters are used as filters
     * @param Response $response the HTTP response to write to
     * @param array $args the URI arguments, contains the parent scientist ID
     *
     * @return Response the JSON encoded list of excavations
     */
    public function handleGetScientistExcavation(Request $request, Response $response, array $args): Response
    {
        return $this->handleGetSubCollection($request, $response, $args, 'getScientistExcavation');
    }

    //* POST
    /**
     * Handles POST /scientists.
     *
     * Creates the list of scientists that is in the JSON body of the request.
     *
     * @param Request $request the incoming HTTP request containing the JSON list of new scientists
     * @param Response $response the HTTP response to write to
     *
     * @return Response a JSON response with the 201 status code if the scientists were created
     */
    function handleCreateScientists(Request $request, Response $response): Response
    {
        return $this->handleCollectionOperation($request, $response, 'doCreateScientists', 'The list of scientists were properly created', 'An error prevented all the scientists from being created', 201);
    }

    //* PUT
    /**
     * Handles PUT /scientists.
     *
     * Updates the list of scientists that is in the JSON body of the request,
     * each item must include the scientist_id of the scientist to update.
     *
     * @param Request $request the incoming HTTP request containing the JSON list of scientists to update
     * @param Response $response the HTTP response to write to
     *
     * @return Response a JSON response with the 200 status code if the scientists were updated
     */
    function handleUpdateScientists(Request $request, Response $response): Response
    {
        return $this->handleCollectionOperation($request, $response, 'doUpdateScientists', 'The list of scientists were properly updated', 'An error prevented all the scientists from being updated');
    }

    //* DELETE
    /**
     * Handles DELETE /scientists.
     *
     * Deletes the scientists whose scientist_id is listed in the JSON body of the request.
     *
     * @param Request $request the incoming HTTP request containing the JSON list of scientist IDs to delete
     * @param Response $response the HTTP response to write to
     *
     * @return Response a JSON response with the 200 status code if the scientists were deleted
     */
    function handleDeleteScientists(Request $request, Response $response): Response
    {
        return $this->handleCollectionOperation($request, $response, 'doDeleteScientists', 'The list of scientists were properly deleted', 'An error prevented all the scientists from being deleted');
    }
}
