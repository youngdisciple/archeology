<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AboutController extends BaseController
{
    private const API_NAME = 'Archeology API';

    private const API_VERSION = '1.0.0';

    public function __construct() {}

    public function handleAboutWebService(Request $request, Response $response): Response
    {
        $data = [
            'api' => self::API_NAME,
            'version' => self::API_VERSION,
            'about' => 'A RESTful web service for accessing and managing archaeological information. The API brings together archaeological sites, artifacts, museums, and scientists in a consistent interface, making it easier to explore records and the relationships between them.',
            'authors' => 'Fairoz Aly, Karim Bennis, Lucas Coveyduck, Daniel Perrotta', // sorted by last names
            'resources' => [
                'artifacts' => [
                    [
                        'path' => 'archeology/artifacts',
                        'methods' => [
                            'GET' => 'List artifacts',
                            'POST' => 'Create artifact(s) from the request body',
                            'PUT' => 'Update existing artifact(s) from the request body',
                            'DELETE' => 'Delete existing artifact(s) from the request body'
                        ]
                    ],
                    [
                        'path' => 'archeology/artifacts/{artifact_id}',
                        'methods' => [
                            'GET' => 'Show a specific artifact',
                            'PUT' => 'Update a specific artifact from the request body',
                            'DELETE' => 'Delete a specific artifact'
                        ]
                    ],
                    // NOTE: Because artifacts is on top of the tree, it doesn't have any subcollection
                ],
                'sites' => [
                    [
                        'path' => 'archeology/sites',
                        'methods' => [
                            'GET' => 'List archeological sites',
                            'POST' => 'Create archeological site(s) from the request body',
                            'PUT' => 'Update an existing archeological site(s) record from the request body',
                            'DELETE' => 'Delete an existing archeological site(s) record from the request body'
                        ]
                    ],
                    [
                        'path' => 'archeology/sites/{site_id}',
                        'methods' => [
                            'GET' => 'Show a specific site'
                        ]
                    ],
                ],
                'museums' => [
                    [
                        'path' => 'archeology/museums',
                        'methods' => [
                            'GET' => 'List museums',
                            'POST' => 'Create museum(s) from the request body',
                            'PUT' => 'Update existing museum(s) from the request body',
                            'DELETE' => 'Delete existing museum(s) from the request body'
                        ]
                    ],
                    [
                        'path' => '/museums/{museum_id}',
                        'methods' => [
                            'GET' => 'Show a specific museum'
                        ]
                    ],
                    [
                        'path' => 'archeology/museums/{museum_id}/scientists',
                        'methods' => [
                            'GET' => 'List scientists associated with a specific museum'
                        ]
                    ],
                    [
                        'path' => 'archeology/museums/{museum_id}/artifacts',
                        'methods' => [
                            'GET' => 'List artifacts belonging to a specific museum'
                        ]
                    ]
                ],
                'scientists' => [
                    [
                        'path' => 'archeology/scientists',
                        'methods' => [
                            'GET' => 'List scientists',
                            'POST' => 'Creates all scientists that are in the body of the request',
                            'PUT' => 'Updates all scientists that are in the body of the request',
                            'DELETE' => 'Deletes all scientists that are in the body of the request'
                        ]
                    ],
                    [
                        'path' => 'archeology/scientists/{scientist_id}',
                        'methods' => [
                            'GET' => 'Show a specific scientist'
                        ]
                    ],
                    [
                        'path' => 'archeology/scientists/{scientist_id}/excavations',
                        'methods' => [
                            'GET' => 'Shows all excavations a scientist was involved in'
                        ]
                    ],
                ]
            ]
        ];

        return $this->renderJson($response, $data);
    }
}
