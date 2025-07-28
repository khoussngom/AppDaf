<?php

use App\Controller\CitoyenController;

return [
    'api/cni' => [
        'controller' => CitoyenController::class,
        'method' => 'searchCitoyen',
        'http_method' => 'GET'
    ]
];
