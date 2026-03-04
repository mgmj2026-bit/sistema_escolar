<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function add(string $method, string $path, array $action): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => rtrim($path, '/') ?: '/',
            'action' => $action,
        ];
    }

    public function dispatch(string $path, string $method): void
    {
        $normalizedPath = rtrim($path, '/') ?: '/';
        $normalizedMethod = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['path'] === $normalizedPath && $route['method'] === $normalizedMethod) {
                [$controllerClass, $controllerMethod] = $route['action'];
                $controller = new $controllerClass();
                $controller->{$controllerMethod}();
                return;
            }
        }

        http_response_code(404);
        echo '404 - Ruta no encontrada';
    }
}
