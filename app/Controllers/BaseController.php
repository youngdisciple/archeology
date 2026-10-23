<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions\InvalidResourceException;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Models\BaseModel;


abstract class BaseController
{

    public function __construct(private BaseModel $base_model) {}
    protected function renderJson(Response $response, array $data, int $status_code = 200): Response
    {
        // var_dump($data);
        $payload = json_encode($data, JSON_UNESCAPED_SLASHES |    JSON_PARTIAL_OUTPUT_ON_ERROR);
        //-- Write JSON data into the response's body.
        $response->getBody()->write($payload);
        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
    }

    /**
     * Helper function to decide whether a given ID is valid
     * cannot be negative
     * must be an INTEGER
     * @param string $id
     * @return bool
     */
    protected function isValidId(string $id): bool
    {
        $pattern = "/^\d{1,9}$/";
        return (preg_match($pattern, $id) === 1);
    }

    /**
     * Helper function to decide whether the given pagination parameters are non-negative integers
     * @param mixed $current_page
     * @param mixed $page_size
     * @return bool
     */
    protected function isValidPagination(?string $current_page, ?string $page_size): bool
    {
        $pattern = "/^\d{1,5}$/";
        return preg_match($pattern, $current_page) === 1 && preg_match($pattern, $page_size) === 1;
    }

    /**
     * A reusable function that can be used to get any collection from the database as long as the collectionName is specified
     * @param Request $request
     * @param Response $response
     * @throws InvalidResourceException
     * @return Response
     */
    protected function handleGetCollection(Request $request, Response $response, string $model_func): Response
    {
        $filters = $request->getQueryParams();
        $current_page = $filters["current_page"] ?? "1";
        $page_size = $filters["page_size"] ?? "10";
        if (!$this->isValidPagination($current_page, $page_size)) {
            throw new InvalidResourceException($request, "Please make sure the current_page and page_size are set to non-negative integer");
        }
        $this->base_model->setPaginationOptions(
            (int)$current_page,
            (int)$page_size
        );
        $collection = $this->base_model->$model_func($filters);
        return $this->renderJson($response, $collection);
    }

    /**
     *A reusable function that gets a singleton resource from a specific table name based on the ID
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @throws InvalidResourceException
     * @return Response
     */
    protected function handleGetCollectionById(Request $request, Response $response, array $args, string $model_func): Response
    {
        $collection_id = $args["collection_id"];
        if (!$this->isValidId($collection_id)) {
            throw new InvalidResourceException($request, "The unique ID $collection_id was invalid! It must be a whole, non-negative integer.");
        }
        $collection = $this->base_model->$model_func((int)$collection_id);
        if ($collection === false) {
            throw new InvalidResourceException($request, "There was no ID record for $collection_id");
        }
        return $this->renderJson($response, $collection);
    }

    protected function handleGetSubCollection(Request $request, Response $response, array $args, string $model_func)
    {
        $filters = $request->getQueryParams();
        $parent_collection_id = $args["parent_collection_id"];
        $current_page = $filters["current_page"] ?? "1";
        $page_size = $filters["page_size"] ?? "10";
        if (!$this->isValidPagination($current_page, $page_size)) {
            throw new InvalidResourceException($request, "Please make sure the current_page and page_size are set to non-negative integer");
        }
        $this->base_model->setPaginationOptions(
            (int)$current_page,
            (int)$page_size
        );
        $parent_collection_id = $args["parent_collection_id"];
        if (!$this->isValidId($parent_collection_id)) {
            throw new InvalidResourceException($request, "The ID $parent_collection_id was invalid! It must be a whole, non-negative integer.");
        }
        $sub_collection = $this->base_model->$model_func($filters, (int) $parent_collection_id);
        if ($sub_collection === false) {
            throw new InvalidResourceException($request, "There was no such ID record for $parent_collection_id");
        }
        return $this->renderJson($response, $sub_collection);
    }
}
