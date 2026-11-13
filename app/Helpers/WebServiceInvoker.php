<?php

namespace App\Helpers;

use App\Exceptions\BadRequestException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class WebServiceInvoker
{
    private array $request_options = [];
    public function __construct(array $options = [])
    {
        $this->request_options = $options;
    }

    public function invokeUri(string $resourceUri)
    {
        $client = new Client();

        try {
            $response = $client->request('GET', $resourceUri, $this->request_options);
        } catch (GuzzleException $ex) {
            throw new Exception('bad request');
        }

        if ($response->getStatusCode() !== 200) {
            throw new Exception("<br> The request failed for this reason: {$response->getStatusCode()} {$response->getReasonPhrase()}");
        }

        $payload = $response->getBody()->getContents();

        $objects = json_decode($payload);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Print or throw an error message/exception.
            throw new Exception();
        }

        if (is_null($objects)) {
            throw new Exception("List is empty");
            exit;
        }

        return $objects;
    }
}
