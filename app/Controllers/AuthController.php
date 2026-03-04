<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Mailer;
use App\Core\Security;
use App\Models\User;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) { $this->redirect('/dashboard'); }
        $this->view('auth/login', ['csrf' => Security::csrfToken()]);
    }

    public function showRegistro(): void
    {
        $this->view('auth/registro', ['csrf' => Security::csrfToken()]);
    }

    public function registro(): void
    {
        if (!Security::requireCsrfToken($_POST['_csrf'] ?? '')) { http_response_code(419); exit('CSRF inválido'); }
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $name = trim((string) ($_POST['name'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $role = (string) ($_POST['role'] ?? 'estudiante');
        $roles = ['director','subdirector','secretaria','auxiliar','docente','estudiante','padre'];

        if (!$email || $name === '' || strlen($password) < 8 || !in_array($role, $roles, true)) {
            $_SESSION['error'] = 'Datos de registro inválidos.';
            $this->redirect('/registro');
        }

        $model = new User();
        $userId = $model->crear(compact('name','email','password','role'));
        $user = $model->findById($userId);
        $enlace = 'http://localhost:8000/verificar?token=' . urlencode((string) $user['verification_token']);
        Mailer::enviar((string) $email, 'Verifica tu cuenta', "Hola {$name}, verifica tu cuenta aquí: {$enlace}");

        $_SESSION['ok'] = 'Cuenta creada. Revisa tu correo para verificar.';
        $this->redirect('/login');
    }

    public function verificar(): void
    {
        $token = (string) ($_GET['token'] ?? '');
        $user = (new User())->verificarPorToken($token);
        if (!$user) { exit('Token inválido o ya usado.'); }

        $mensaje = "Tu cuenta fue verificada correctamente. Usuario: {$user['email']}. Ya puedes iniciar sesión en http://localhost:8000/login";
        Mailer::enviar((string) $user['email'], 'Cuenta verificada y accesos', $mensaje);

        $_SESSION['ok'] = 'Cuenta verificada con éxito.';
        $this->redirect('/login');
    }

    public function login(): void
    {
        if (!Security::requireCsrfToken($_POST['_csrf'] ?? '')) { http_response_code(419); exit('CSRF token inválido'); }
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || $password === '' || !Auth::attempt($email, $password)) {
            $_SESSION['error'] = 'Credenciales incorrectas o cuenta no verificada.';
            $this->redirect('/login');
        }
        $this->redirect('/dashboard');
    }

    public function cambiarContrasena(): void
    {
        Auth::requireAuth();
        if (!Security::requireCsrfToken($_POST['_csrf'] ?? '')) { http_response_code(419); exit('CSRF token inválido'); }
        $clave = (string) ($_POST['password'] ?? '');
        if (strlen($clave) < 8) { exit('La contraseña debe tener mínimo 8 caracteres.'); }

        $user = Auth::user();
        (new User())->cambiarContrasena((int) $user['id'], $clave);
        Mailer::enviar((string) $user['email'], 'Notificación de cambio de contraseña', "Se cambió tu contraseña a las " . date('H:i') . ". Usuario de acceso: {$user['email']} - URL: http://localhost:8000/login");

        $_SESSION['ok'] = 'Contraseña actualizada y notificada por correo.';
        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }
}
