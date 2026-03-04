<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;

final class ReportController extends Controller
{
    public function index(): void
    {
        Auth::requireRoles(['director','subdirector','secretaria','auxiliar']);
        $this->view('modules/reportes', ['user' => Auth::user()]);
    }

    public function asistenciaEstudiantesCsv(): void
    {
        Auth::requireRoles(['director','subdirector','secretaria','auxiliar']);
        $rows = Database::connection()->query('SELECT u.name estudiante, a.fecha, a.estado, t.name docente FROM attendance_students a INNER JOIN users u ON u.id = a.student_id INNER JOIN users t ON t.id = a.teacher_id ORDER BY a.fecha DESC')->fetchAll();
        $this->csv('reporte_asistencia_estudiantes.csv', $rows);
    }

    public function asistenciaPersonalCsv(): void
    {
        Auth::requireRoles(['director','subdirector','secretaria','auxiliar']);
        $rows = Database::connection()->query('SELECT u.name personal, u.role cargo, a.fecha, a.hora FROM attendance_staff a INNER JOIN users u ON u.id = a.staff_id ORDER BY a.fecha DESC')->fetchAll();
        $this->csv('reporte_asistencia_personal.csv', $rows);
    }

    private function csv(string $filename, array $rows): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        $out = fopen('php://output', 'w');
        if (!empty($rows)) {
            fputcsv($out, array_keys($rows[0]));
            foreach ($rows as $r) {
                fputcsv($out, $r);
            }
        }
        fclose($out);
    }
}
