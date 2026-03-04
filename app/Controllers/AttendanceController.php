<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Security;

final class AttendanceController extends Controller
{
    public function estudiantes(): void
    {
        Auth::requireRoles(['docente']);
        $db = Database::connection();
        $secciones = $db->prepare('SELECT s.id, CONCAT(g.name, " - ", s.name) nombre FROM teacher_sections ts INNER JOIN sections s ON s.id = ts.section_id INNER JOIN grades g ON g.id = s.grade_id WHERE ts.teacher_id = :id');
        $secciones->execute(['id' => Auth::user()['id']]);

        $this->view('modules/asistencia_estudiantes', [
            'csrf' => Security::csrfToken(),
            'secciones' => $secciones->fetchAll(),
            'user' => Auth::user(),
        ]);
    }

    public function guardarEstudiante(): void
    {
        Auth::requireRoles(['docente']);
        if (!Security::requireCsrfToken($_POST['_csrf'] ?? '')) { exit('CSRF inválido'); }
        $db = Database::connection();
        $stmt = $db->prepare('INSERT INTO attendance_students (student_id, teacher_id, fecha, estado) VALUES (:student_id, :teacher_id, CURDATE(), :estado)');
        $stmt->execute(['student_id' => (int) $_POST['student_id'], 'teacher_id' => (int) Auth::user()['id'], 'estado' => $_POST['estado'] ?? 'presente']);
        $this->redirect('/asistencia/estudiantes');
    }

    public function miAsistencia(): void
    {
        Auth::requireRoles(['estudiante']);
        $stmt = Database::connection()->prepare('SELECT fecha, estado, created_at FROM attendance_students WHERE student_id = :id ORDER BY fecha DESC');
        $stmt->execute(['id' => Auth::user()['id']]);
        $this->view('modules/mi_asistencia', ['registros' => $stmt->fetchAll(), 'user' => Auth::user()]);
    }

    public function personal(): void
    {
        Auth::requireRoles(['docente','subdirector','secretaria','auxiliar','director']);
        $this->view('modules/asistencia_personal', ['csrf' => Security::csrfToken(), 'user' => Auth::user()]);
    }

    public function guardarPersonal(): void
    {
        Auth::requireRoles(['docente','subdirector','secretaria','auxiliar','director']);
        if (!Security::requireCsrfToken($_POST['_csrf'] ?? '')) { exit('CSRF inválido'); }
        $stmt = Database::connection()->prepare('INSERT INTO attendance_staff (staff_id, fecha, hora) VALUES (:id, CURDATE(), CURTIME())');
        $stmt->execute(['id' => Auth::user()['id']]);
        $_SESSION['voz'] = Auth::user()['name'] . ' marcó asistencia a las ' . date('h:i A');
        $this->redirect('/asistencia/personal');
    }
}
