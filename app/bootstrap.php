<?php
namespace App;
use App\Core\Router;
use App\Core\Database;
use App\Core\Container;
use App\Services\CitoyenService;
use App\Controller\CitoyenController;
use App\Repository\CitoyenRepository;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/core/Container.php';
require_once __DIR__ . '/core/Database.php';

$container = new \App\Core\Container();

$container->set('database', function () {
    $config = require __DIR__ . '/config/configDatabase.php';
    $database = new Database($config);
    return $database->connectDatabase(); 
});

$container->set(CitoyenRepository::class, function ($container) {
    return new CitoyenRepository($container->get('database'));
});

$container->set(CitoyenService::class, function ($container) {
    return new CitoyenService($container->get(CitoyenRepository::class));
});

$container->set('router', function ($container) {
    $routes = require __DIR__ . '/../router/routes.php';
    return new \App\Router($routes, $container);
});

$container->set(CitoyenController::class, function ($container) {
    return new CitoyenController($container->get(CitoyenService::class));
});

return $container;
