<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class WebServiceInvoker
{
    private array $request_options = [];
    public function __construct(array $options)
    {
        $this->request_options = $options;
    }

    public static function invokeUri(string $resourceUri) {
        $client = new Client();

        try {
            $response = $client->request('GET', $resourceUri);
        } catch (GuzzleException $ex) {
            // Print or throw an error message/exception.
        }

        if ($response->getStatusCode() !== 200) {
            echo "<br> The request failed for this reason:
                {$response->getStatusCode()}
                {$response->getReasonPhrase()}";
            //throw new Exception("<br> The request failed for this reason: {$response->getStatusCode()} {$response->getReasonPhrase()}");
        }

        $payload = $response->getBody()->getContents();

        $objects = json_decode($payload);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Print or throw an error message/exception.
        }


        if (is_null($objects)) {
            echo "The list is empty";
            exit;
        }

    }
}
