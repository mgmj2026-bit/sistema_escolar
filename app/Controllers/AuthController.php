<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Security;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }

        $this->view('auth/login', ['csrf' => Security::csrfToken()]);
    }

    public function login(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (!Security::requireCsrfToken($token)) {
            http_response_code(419);
            exit('CSRF token inválido');
        }

        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || $password === '' || !Auth::attempt($email, $password)) {
            $_SESSION['error'] = 'Credenciales incorrectas';
            $this->redirect('/login');
        }

        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }
}
