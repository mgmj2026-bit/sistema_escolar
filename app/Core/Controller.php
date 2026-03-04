<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = base_path('app/Views/' . $view . '.php');

        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo 'Vista no encontrada';
            return;
        }

        require base_path('app/Views/layouts/app.php');
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}
