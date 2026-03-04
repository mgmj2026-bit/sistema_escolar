<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Audit;
use App\Core\Auth;
use App\Core\Captcha;
use App\Core\Controller;
use App\Core\Mailer;
use App\Core\RateLimiter;
use App\Core\Security;
use App\Models\User;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) { $this->redirect('/dashboard'); }
        $this->view('auth/login', ['csrf' => Security::csrfToken(), 'captcha' => Captcha::generar()['codigo']]);
    }

    public function showRegistro(): void
    {
        $this->view('auth/registro', ['csrf' => Security::csrfToken(), 'captcha' => Captcha::generar()['codigo']]);
    }

    public function registro(): void
    {
        if (!RateLimiter::hit('registro', 5, 300)) { exit('Demasiados intentos. Intenta luego.'); }
        if (!Security::requireCsrfToken($_POST['_csrf'] ?? '')) { http_response_code(419); exit('CSRF inválido'); }
        if (!Captcha::validar((string) ($_POST['captcha'] ?? ''))) { $_SESSION['error'] = 'CAPTCHA inválido.'; $this->redirect('/registro'); }

        $dni = trim((string) ($_POST['dni'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $password = (string) ($_POST['password'] ?? '');

        if (!preg_match('/^\d{8}$/', $dni) || !$email || strlen($password) < 8) {
            $_SESSION['error'] = 'Datos inválidos.';
            $this->redirect('/registro');
        }

        $model = new User();
        $persona = $model->buscarPersonaPorDni($dni);
        if (!$persona) {
            $_SESSION['error'] = 'El DNI no existe en el padrón institucional.';
            $this->redirect('/registro');
        }

        if ($model->findByEmail((string)$email)) {
            $_SESSION['error'] = 'El correo ya está registrado.';
            $this->redirect('/registro');
        }

        $userId = $model->crearDesdeDni($persona, (string) $email, $password);
        $user = $model->findById($userId);
        $enlace = 'http://localhost:8000/verificar?token=' . urlencode((string) $user['verification_token']);
        Mailer::enviar((string) $email, 'Verifica tu cuenta', "Hola {$persona['name']}, verifica tu cuenta aquí: {$enlace}");
        Audit::log($userId, 'registro_creado', 'Cuenta creada pendiente de verificación');

        $_SESSION['ok'] = 'Cuenta creada según tu rol institucional: ' . $persona['role'] . '. Revisa tu correo para verificar.';
        $this->redirect('/login');
    }

    public function verificar(): void
    {
        $token = (string) ($_GET['token'] ?? '');
        $user = (new User())->verificarPorToken($token);
        if (!$user) { exit('Token inválido o ya usado.'); }

        $mensaje = "Tu cuenta fue verificada correctamente. Usuario: {$user['email']}. Ya puedes iniciar sesión en http://localhost:8000/login";
        Mailer::enviar((string) $user['email'], 'Cuenta verificada y accesos', $mensaje);
        Audit::log((int) $user['id'], 'cuenta_verificada', 'Usuario verificado por correo');

        $_SESSION['ok'] = 'Cuenta verificada con éxito.';
        $this->redirect('/login');
    }

    public function login(): void
    {
        if (!RateLimiter::hit('login', 7, 300)) { exit('Demasiados intentos de login.'); }
        if (!Security::requireCsrfToken($_POST['_csrf'] ?? '')) { http_response_code(419); exit('CSRF token inválido'); }
        if (!Captcha::validar((string) ($_POST['captcha'] ?? ''))) { $_SESSION['error'] = 'CAPTCHA inválido.'; $this->redirect('/login'); }

        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $user = $email ? Auth::attemptPassword($email, $password) : null;

        if (!$user) {
            Audit::log(null, 'login_fallido', 'Credenciales inválidas');
            $_SESSION['error'] = 'Credenciales incorrectas o cuenta no verificada.';
            $this->redirect('/login');
        }

        $codigo2fa = (string) random_int(100000, 999999);
        (new User())->setTwoFactorCode((int) $user['id'], $codigo2fa);
        Mailer::enviar((string) $user['email'], 'Código de acceso 2FA', "Tu código de segundo factor es: {$codigo2fa} (válido por 10 minutos)");

        $_SESSION['2fa_user_id'] = (int) $user['id'];
        $_SESSION['2fa_ok'] = 'Se envió código 2FA a tu correo.';
        $this->redirect('/2fa');
    }

    public function show2fa(): void
    {
        if (empty($_SESSION['2fa_user_id'])) { $this->redirect('/login'); }
        $this->view('auth/2fa', ['csrf' => Security::csrfToken()]);
    }

    public function verify2fa(): void
    {
        if (!Security::requireCsrfToken($_POST['_csrf'] ?? '')) { exit('CSRF inválido'); }
        $userId = (int) ($_SESSION['2fa_user_id'] ?? 0);
        $code = trim((string) ($_POST['code'] ?? ''));
        if ($userId <= 0 || !preg_match('/^\d{6}$/', $code)) { exit('Código inválido'); }

        $userModel = new User();
        if (!$userModel->validarTwoFactor($userId, $code)) {
            Audit::log($userId, '2fa_fallido', 'Código incorrecto');
            $_SESSION['error'] = 'Código 2FA incorrecto o expirado.';
            $this->redirect('/2fa');
        }

        $user = $userModel->findById($userId);
        Auth::loginUser($user);
        Audit::log($userId, 'login_exitoso', 'Ingreso con 2FA');
        unset($_SESSION['2fa_user_id'], $_SESSION['2fa_ok']);
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
        Audit::log((int) $user['id'], 'password_change', 'Cambio de contraseña notificado');

        $_SESSION['ok'] = 'Contraseña actualizada y notificada por correo.';
        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }
}
