<?php

use App\Controllers\AuthController;
use App\Controllers\ChatController;
use App\Controllers\DashboardController;
use App\Controllers\ModuleController;

return [
    ['GET', '/', [DashboardController::class, 'index']],
    ['GET', '/login', [AuthController::class, 'showLogin']],
    ['POST', '/login', [AuthController::class, 'login']],
    ['POST', '/logout', [AuthController::class, 'logout']],

    ['GET', '/dashboard', [DashboardController::class, 'index']],
    ['GET', '/modulo', [ModuleController::class, 'show']],

    ['GET', '/chat', [ChatController::class, 'index']],
    ['POST', '/chat', [ChatController::class, 'send']],
    ['GET', '/chat/poll', [ChatController::class, 'poll']],
];
