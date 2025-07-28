<?php

require_once __DIR__ . '/../app/bootstrap.php';

$container = require __DIR__ . '/../app/bootstrap.php';

$router = $container->get('router');


$router->handleRequest();
