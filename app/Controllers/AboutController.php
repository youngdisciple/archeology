<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AboutController extends BaseController
{
    private const API_NAME = 'archeology';

    private const API_VERSION = '1.0.0';

    public function __construct() {}

    public function handleAboutWebService(Request $request, Response $response): Response
    {
        $data = [
            'api' => self::API_NAME,
            'version' => self::API_VERSION,
            'about' => 'This is an api that provides information about archeology',
            'author' => 'Fairoz Aly, Karim Bennis, Lucas Coveyduck, Daniel Perrotta', // sorted by last names
            'resources' => [
                'artifacts' => [
                    [
                        'path' => '/artifacts',
                        'methods' => [
                            'GET' => 'List artifacts'
                            // TODO: eventually, add different methods like POST
                            // and describe what they would do
                        ]
                    ],
                    [
                        'path' => '/artifacts/{artifact_id}',
                        'methods' => [
                            'GET' => 'Show a specific artifact'
                        ]
                    ],
                    // NOTE: Because artifacts is on top of the tree, it doesn't have any subcollection
                ],
                'sites' => [
                    [
                        'path' => '/sites',
                        'methods' => [
                            'GET' => 'List archeological sites'
                        ]
                    ],
                    [
                        'path' => '/sites/{site_id}',
                        'methods' => [
                            'GET' => 'Show a specific site'
                        ]
                    ],
                ],
                'museums' => [
                    [
                        'path' => '/museums',
                        'methods' => [
                            'GET' => 'List museums'
                        ]
                    ],
                    [
                        'path' => '/museums/{museum_id}',
                        'methods' => [
                            'GET' => 'Show a specific museum'
                        ]
                    ]
                ],
                'scientists' => [
                    [
                        'path' => '/scientists',
                        'methods' => [
                            'GET' => 'List scientists'
                        ]
                    ],
                    [
                        'path' => '/scientists/{scientist_id}',
                        'methods' => [
                            'GET' => 'Show a specific scientist'
                        ]
                    ],
                    [
                        'path' => '/scientists/{scientist_id}/excavations',
                        'methods' => [
                            'GET' => 'Shows all excavations a scientist was involved in'
                        ]
                    ]
                ]
            ]
        ];

        return $this->renderJson($response, $data);
    }
}
