<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Security;
use App\Models\ChatMessage;

final class ChatController extends Controller
{
    public function index(): void
    {
        Auth::requireAuth();
        $chat = new ChatMessage();

        $this->view('modules/chat', [
            'messages' => $chat->latest(),
            'csrf' => Security::csrfToken(),
            'user' => Auth::user(),
        ]);
    }

    public function send(): void
    {
        Auth::requireAuth();

        if (!Security::requireCsrfToken($_POST['_csrf'] ?? '')) {
            http_response_code(419);
            exit('CSRF inválido');
        }

        $body = trim((string) ($_POST['body'] ?? ''));
        if ($body === '' || mb_strlen($body) > 500) {
            http_response_code(422);
            exit('Mensaje inválido');
        }

        (new ChatMessage())->create((int) Auth::user()['id'], $body);
        $this->redirect('/chat');
    }

    public function poll(): void
    {
        Auth::requireAuth();
        $lastId = (int) ($_GET['last_id'] ?? 0);
        $messages = (new ChatMessage())->since($lastId);

        header('Content-Type: application/json');
        echo json_encode(['messages' => $messages], JSON_UNESCAPED_UNICODE);
    }
}
