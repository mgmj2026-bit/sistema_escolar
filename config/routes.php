<?php

use App\Controllers\AttendanceController;
use App\Controllers\AuthController;
use App\Controllers\ChatController;
use App\Controllers\DashboardController;
use App\Controllers\ModuleController;
use App\Controllers\ReportController;

return [
    ['GET', '/', [DashboardController::class, 'index']],
    ['GET', '/login', [AuthController::class, 'showLogin']],
    ['POST', '/login', [AuthController::class, 'login']],
    ['GET', '/registro', [AuthController::class, 'showRegistro']],
    ['POST', '/registro', [AuthController::class, 'registro']],
    ['GET', '/verificar', [AuthController::class, 'verificar']],
    ['POST', '/cambiar-clave', [AuthController::class, 'cambiarContrasena']],
    ['POST', '/logout', [AuthController::class, 'logout']],

    ['GET', '/dashboard', [DashboardController::class, 'index']],
    ['GET', '/modulo', [ModuleController::class, 'show']],

    ['GET', '/chat', [ChatController::class, 'index']],
    ['POST', '/chat', [ChatController::class, 'enviar']],
    ['GET', '/chat/poll', [ChatController::class, 'poll']],
    ['GET', '/chat/estudiantes', [ChatController::class, 'estudiantesPorSeccion']],

    ['GET', '/asistencia/estudiantes', [AttendanceController::class, 'estudiantes']],
    ['POST', '/asistencia/estudiantes', [AttendanceController::class, 'guardarEstudiante']],
    ['GET', '/asistencia/mi', [AttendanceController::class, 'miAsistencia']],
    ['GET', '/asistencia/personal', [AttendanceController::class, 'personal']],
    ['POST', '/asistencia/personal', [AttendanceController::class, 'guardarPersonal']],

    ['GET', '/reportes', [ReportController::class, 'index']],
    ['GET', '/reportes/asistencia-estudiantes.csv', [ReportController::class, 'asistenciaEstudiantesCsv']],
    ['GET', '/reportes/asistencia-personal.csv', [ReportController::class, 'asistenciaPersonalCsv']],
];
