<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Module;

final class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireAuth();

        $modules = (new Module())->all();

        $this->view('dashboard/index', [
            'user' => Auth::user(),
            'modules' => $modules,
        ]);
    }
}
