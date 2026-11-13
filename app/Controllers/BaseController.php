<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Domain\Models\BaseModel;
use App\Domain\Services\BaseService;
use App\Exceptions\UnprocessableEntityException;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions\BadRequestException;
use App\Validation\ValidationHelper;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\WebServiceInvoker;


abstract class BaseController
{
    public function __construct(private BaseModel $base_model, private BaseService $base_service) {}

    protected function renderJson(Response $response, array $data, int $status_code = 200): Response
    {
        // var_dump($data);
        $payload = json_encode($data, JSON_UNESCAPED_SLASHES |    JSON_PARTIAL_OUTPUT_ON_ERROR);
        //-- Write JSON data into the response's body.
        $response->getBody()->write($payload);
        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
    }

    /**
     * A reusable function that can be used to get any collection from the database as long as the collectionName is specified
     * @param Request $request
     * @param Response $response
     * @throws BadRequestException
     * @return Response
     */
    protected function handleGetCollection(Request $request, Response $response, string $model_func, array $uri_args): Response
    {
        $filters = $request->getQueryParams();
        $current_page = $filters["current_page"] ?? "1";
        $page_size = $filters["page_size"] ?? "10";
        if (!ValidationHelper::isValidPagination($current_page, $page_size)) {
            throw new BadRequestException($request, "Please make sure the current_page and page_size are set to non-negative integer");
        }
        $this->base_model->setPaginationOptions(
            (int)$current_page,
            (int)$page_size
        );
        try {
            $collection = $this->base_model->$model_func($filters);
            $api_data = $this->getListOfComposites();
        } catch (\InvalidArgumentException $e) {
            throw new UnprocessableEntityException($request, $e->getMessage());
        }
        $api_data = $this->base_model->$model_func($filters);
        return $this->renderJson($response, $collection);
    }

    protected function getListOfComposites(string $uri): mixed
    {
        $data = WebServiceInvoker::invokeUri($uri);
        return $data;
    }

    /**
     *A reusable function that gets a singleton resource from a specific table name based on the ID and specified string function that handles the SQL statements
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @throws BadRequestException
     * @return Response
     */
    protected function handleGetCollectionById(Request $request, Response $response, array $args, string $model_func): Response
    {
        $collection_id = $args["collection_id"];
        if (!ValidationHelper::isValidId($collection_id)) {
            throw new BadRequestException($request, "The unique ID $collection_id was invalid! It must be a whole, non-negative integer.");
        }
        $collection = $this->base_model->$model_func((int)$collection_id);
        if ($collection === false) {
            throw new BadRequestException($request, "There was no ID record for $collection_id");
        }
        return $this->renderJson($response, $collection);
    }

    /**
     * A reusable function that allows its children to send requests and get responses about a sub-collection of a collection so long as the model function string name is specified
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @param string $model_func
     * @throws BadRequestException
     * @return Response
     */
    protected function handleGetSubCollection(Request $request, Response $response, array $args, string $model_func)
    {
        $filters = $request->getQueryParams();
        $parent_collection_id = $args["parent_collection_id"];
        $current_page = $filters["current_page"] ?? "1";
        $page_size = $filters["page_size"] ?? "10";
        if (!ValidationHelper::isValidPagination($current_page, $page_size)) {
            throw new BadRequestException($request, "Please make sure the current_page and page_size are set to non-negative integer");
        }
        $this->base_model->setPaginationOptions(
            (int)$current_page,
            (int)$page_size
        );
        $parent_collection_id = $args["parent_collection_id"];
        if (!ValidationHelper::isValidId($parent_collection_id)) {
            throw new BadRequestException($request, "The ID $parent_collection_id was invalid! It must be a whole, non-negative integer.");
        }
        try {
            $sub_collection = $this->base_model->$model_func($filters, (int) $parent_collection_id);
        } catch (\InvalidArgumentException $e) {
            throw new UnprocessableEntityException($request, $e->getMessage());
        }
        if ($sub_collection === false) {
            throw new BadRequestException($request, "There was no such ID record for $parent_collection_id");
        }
        return $this->renderJson($response, $sub_collection);
    }

    /**
     * A jack of trades type of function that handles POST, PUT , DELETE operations
     * @param Request $request
     * @param Response $response
     * @param string $service_func is the string name of your service functions for whatever operation you want to do
     * @param string $success_msg
     * @param string $err_msg
     * @param int $success_code is 200 by default unless it is POST which is 201
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     * @return Response
     */
    protected function handleCollectionOperation(Request $request, Response $response, string $service_func, string $success_msg, string $err_msg, int $success_code = 200): Response
    {
        $body = $request->getParsedBody();
        $payload = is_array($body) ? $body : throw new BadRequestException($request); //making sure body request isn't empty or it causes a 500 error
        //try{
        $result = $this->base_service->$service_func($payload);
        //}catch(){

        //}
        $response_data = [];
        if ($result->isSuccess()) {
            $response_data = [
                "code" => $success_code,
                "message" => $success_msg
            ];
            return $this->renderJson($response, $response_data, $success_code);
        }
        if ($result->isFailure()) {
            throw (new UnprocessableEntityException($request, $err_msg));
            /*
            $code = 400;
            $response_data = [
                "code" => $code,
                "status" => "error",
                "message" => $err_msg,
                "details" => $result->getErrors()
            ];

            return $this->renderJson($response, $response_data, $code);
            */
        }
        return $response;
    }
}
