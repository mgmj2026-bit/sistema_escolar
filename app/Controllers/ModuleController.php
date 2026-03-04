<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Module;

final class ModuleController extends Controller
{
    public function show(): void
    {
        Auth::requireAuth();

        $slug = $_GET['slug'] ?? '';
        $modules = (new Module())->all();
        $module = null;

        foreach ($modules as $item) {
            if ($item['slug'] === $slug) {
                $module = $item;
                break;
            }
        }

        if (!$module) {
            http_response_code(404);
            exit('Módulo no encontrado');
        }

        $this->view('modules/show', [
            'module' => $module,
            'user' => Auth::user(),
        ]);
    }
}
