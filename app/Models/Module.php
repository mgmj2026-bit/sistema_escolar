<?php

declare(strict_types=1);

namespace App\Models;

final class Module
{
    public function all(): array
    {
        return [
            ['slug' => 'estudiantes', 'icon' => 'bi-people-fill', 'title' => 'Estudiantes', 'desc' => 'Gestión integral del historial académico y conducta.'],
            ['slug' => 'padres', 'icon' => 'bi-person-hearts', 'title' => 'Padres', 'desc' => 'Portal de tutores con seguimiento de rendimiento y pagos.'],
            ['slug' => 'docentes', 'icon' => 'bi-person-workspace', 'title' => 'Docentes', 'desc' => 'Planificación, asistencia y evaluación inteligente.'],
            ['slug' => 'administradores', 'icon' => 'bi-shield-lock-fill', 'title' => 'Administradores', 'desc' => 'Control global, auditoría y monitoreo continuo.'],
            ['slug' => 'servicios', 'icon' => 'bi-tools', 'title' => 'Personal de servicios', 'desc' => 'Tickets de mantenimiento, limpieza y logística.'],
            ['slug' => 'finanzas', 'icon' => 'bi-currency-dollar', 'title' => 'Finanzas', 'desc' => 'Facturación, cartera, costos y conciliación bancaria.'],
            ['slug' => 'reportes', 'icon' => 'bi-file-earmark-bar-graph-fill', 'title' => 'Reportes', 'desc' => 'KPIs y analítica institucional en tiempo real.'],
            ['slug' => 'matriculas', 'icon' => 'bi-journal-check', 'title' => 'Matrículas', 'desc' => 'Inscripción digital con validaciones anti-fraude.'],
            ['slug' => 'anios-academicos', 'icon' => 'bi-calendar3', 'title' => 'Años académicos', 'desc' => 'Calendarios, periodos y cierres académicos seguros.'],
            ['slug' => 'grados', 'icon' => 'bi-diagram-3-fill', 'title' => 'Grados', 'desc' => 'Estructura curricular por nivel.'],
            ['slug' => 'secciones', 'icon' => 'bi-grid-3x3-gap-fill', 'title' => 'Secciones', 'desc' => 'Distribución inteligente de alumnos por capacidad.'],
            ['slug' => 'aula-virtual', 'icon' => 'bi-camera-video-fill', 'title' => 'Aula virtual', 'desc' => 'Clases híbridas, repositorio y videoconferencias.'],
            ['slug' => 'biblioteca', 'icon' => 'bi-book-half', 'title' => 'Biblioteca', 'desc' => 'Catálogo, préstamos y trazabilidad de recursos.'],
            ['slug' => 'asignaciones', 'icon' => 'bi-card-checklist', 'title' => 'Asignaciones', 'desc' => 'Tareas con rúbricas automáticas y retroalimentación.'],
            ['slug' => 'salud-bienestar', 'icon' => 'bi-heart-pulse-fill', 'title' => 'Salud y bienestar', 'desc' => 'Ficha médica, alertas y acompañamiento psicosocial.'],
            ['slug' => 'transporte', 'icon' => 'bi-bus-front-fill', 'title' => 'Transporte', 'desc' => 'Rutas, asistencia y geocercas en tiempo real.'],
            ['slug' => 'inventario', 'icon' => 'bi-box-seam-fill', 'title' => 'Inventario', 'desc' => 'Activos, depreciación y alertas de stock crítico.'],
            ['slug' => 'compliance', 'icon' => 'bi-clipboard2-check-fill', 'title' => 'Compliance', 'desc' => 'Políticas, evidencias y cumplimiento normativo.'],
            ['slug' => 'chat', 'icon' => 'bi-chat-left-dots-fill', 'title' => 'Chat súper en vivo', 'desc' => 'Mensajería instantánea con sonido por mensaje nuevo.'],
        ];
    }
}
