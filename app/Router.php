<?php
namespace App;

use ReflectionClass;

class Router
{
    private array $routes;
    private \App\Core\Container $container;

    public function __construct(array $routes, \App\Core\Container $container)
    {
        $this->routes = $routes;
        $this->container = $container;
    }

    public function handleRequest(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        $parsedUrl = parse_url($uri);
        $path = trim($parsedUrl['path'], '/');

        if (!isset($this->routes[$path])) {
            http_response_code(404);
            echo json_encode(['error' => 'Route non trouvée']);
            return;
        }

        $route = $this->routes[$path];
        $controllerClass = $route['controller'];
        $controllerMethod = $route['method'];

        if (!$controllerClass || !$controllerMethod) {
            http_response_code(500);
            echo json_encode(['error' => 'Route mal définie']);
            return;
        }

        if ($route['http_method'] !== $method) {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode HTTP non autorisée']);
            return;
        }

        $controller = $this->container->get($controllerClass);

        if (!method_exists($controller, $controllerMethod)) {
            http_response_code(500);
            echo json_encode(['error' => 'Méthode non trouvée']);
            return;
        }

        $controller->$controllerMethod();
    }
}
