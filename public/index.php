<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../app/Core/helpers.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

$config = require __DIR__ . '/../config/app.php';

use App\Core\Router;
use App\Core\Security;

Security::applyDefaultHeaders($config['security']);

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$autoBase = preg_replace('#/public$#', '', rtrim($scriptDir, '/')) ?: '';
$configuredBase = trim((string) env('APP_BASE_URL', ''));
$_SERVER['APP_BASE_URL'] = $configuredBase !== '' ? '/' . trim($configuredBase, '/') : $autoBase;

$router = new Router();
$routes = require __DIR__ . '/../config/routes.php';

foreach ($routes as $route) {
    $router->add($route[0], $route[1], $route[2]);
}

$requestUriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$base = app_base_url();
$path = $requestUriPath;

if ($base !== '' && str_starts_with($path, $base)) {
    $path = substr($path, strlen($base)) ?: '/';
}

if (str_starts_with($path, '/public/')) {
    $path = substr($path, strlen('/public')) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$router->dispatch($path, $method);
