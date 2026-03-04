<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Security;
use App\Models\ChatMessage;
use App\Models\User;

final class ChatController extends Controller
{
    public function index(): void
    {
        Auth::requireAuth();
        $usuario = Auth::user();
        $userModel = new User();
        $contactos = $userModel->contactosPermitidos($usuario);

        $contactoId = (int) ($_GET['contacto'] ?? 0);
        $mensajes = [];
        if ($contactoId > 0 && $this->contactoPermitido($contactos, $contactoId)) {
            $mensajes = (new ChatMessage())->conversacion((int) $usuario['id'], $contactoId);
        }

        $this->view('modules/chat', [
            'user' => $usuario,
            'csrf' => Security::csrfToken(),
            'contactos' => $contactos,
            'contactoId' => $contactoId,
            'mensajes' => $mensajes,
            'secciones' => $usuario['role'] === 'docente' ? $userModel->seccionesDocente((int) $usuario['id']) : [],
        ]);
    }

    public function enviar(): void
    {
        Auth::requireAuth();
        if (!Security::requireCsrfToken($_POST['_csrf'] ?? '')) { http_response_code(419); exit('CSRF inválido'); }
        $usuario = Auth::user();
        $destino = (int) ($_POST['receiver_id'] ?? 0);
        $body = trim((string) ($_POST['body'] ?? ''));
        if ($body === '' || mb_strlen($body) > 500) { http_response_code(422); exit('Mensaje inválido'); }

        $contactos = (new User())->contactosPermitidos($usuario);
        if (!$this->contactoPermitido($contactos, $destino)) { http_response_code(403); exit('No puedes chatear con este usuario.'); }

        (new ChatMessage())->enviar((int) $usuario['id'], $destino, $body);
        $this->redirect('/chat?contacto=' . $destino);
    }

    public function poll(): void
    {
        Auth::requireAuth();
        $usuario = Auth::user();
        $destino = (int) ($_GET['contacto'] ?? 0);
        $lastId = (int) ($_GET['last_id'] ?? 0);
        $contactos = (new User())->contactosPermitidos($usuario);

        header('Content-Type: application/json');
        if (!$this->contactoPermitido($contactos, $destino)) {
            echo json_encode(['messages' => []]);
            return;
        }

        echo json_encode(['messages' => (new ChatMessage())->desde((int) $usuario['id'], $destino, $lastId)], JSON_UNESCAPED_UNICODE);
    }

    public function estudiantesPorSeccion(): void
    {
        Auth::requireRoles(['docente']);
        $sectionId = (int) ($_GET['seccion_id'] ?? 0);
        $lista = (new User())->estudiantesPorSeccionDocente((int) Auth::user()['id'], $sectionId);
        header('Content-Type: application/json');
        echo json_encode(['estudiantes' => $lista], JSON_UNESCAPED_UNICODE);
    }

    private function contactoPermitido(array $contactos, int $contactoId): bool
    {
        foreach ($contactos as $grupo) {
            foreach ($grupo as $u) {
                if ((int) $u['id'] === $contactoId) {
                    return true;
                }
            }
        }
        return false;
    }
}
