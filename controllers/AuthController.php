<?php
// ============================================================
// CONTROLADOR: Autenticación
// ============================================================
require_once APP_ROOT . '/models/Usuario.php';

class AuthController {
    private Usuario $usuario;

    public function __construct() {
        $this->usuario = new Usuario();
    }

    public function login(): void {
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }
        require_once APP_ROOT . '/views/auth/login.php';
    }

    public function register(): void {
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }
        require_once APP_ROOT . '/views/auth/register.php';
    }

    public function proceso_login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }
        $correo    = trim($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';

        if (empty($correo) || empty($contrasena)) {
            $_SESSION['error'] = 'Por favor completa todos los campos.';
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }

        $user = $this->usuario->buscarPorCorreo($correo);

        if ($user && $this->usuario->verificarContrasena($contrasena, $user['contrasena'])) {
            $_SESSION['usuario_id']  = $user['id'];
            $_SESSION['nombre']      = $user['nombre'];
            $_SESSION['rol']         = $user['rol'];
            $_SESSION['exito']       = '¡Bienvenido, ' . $user['nombre'] . '!';
            header('Location: ' . APP_URL . '/dashboard');
        } else {
            $_SESSION['error'] = 'Correo o contraseña incorrectos.';
            header('Location: ' . APP_URL . '/auth/login');
        }
        exit;
    }

    public function proceso_registro(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/auth/register');
            exit;
        }
        $nombre    = trim($_POST['nombre'] ?? '');
        $correo    = trim($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        $confirm   = $_POST['confirmar'] ?? '';

        if (empty($nombre) || empty($correo) || empty($contrasena)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            header('Location: ' . APP_URL . '/auth/register');
            exit;
        }
        if ($contrasena !== $confirm) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            header('Location: ' . APP_URL . '/auth/register');
            exit;
        }
        if (strlen($contrasena) < 6) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres.';
            header('Location: ' . APP_URL . '/auth/register');
            exit;
        }
        if ($this->usuario->correoExiste($correo)) {
            $_SESSION['error'] = 'Este correo ya está registrado.';
            header('Location: ' . APP_URL . '/auth/register');
            exit;
        }
        if ($this->usuario->crear(['nombre' => $nombre,'correo' => $correo,'contrasena' => $contrasena])) {
            $_SESSION['exito'] = 'Cuenta creada correctamente. ¡Inicia sesión!';
            header('Location: ' . APP_URL . '/auth/login');
        } else {
            $_SESSION['error'] = 'Error al crear la cuenta. Intenta de nuevo.';
            header('Location: ' . APP_URL . '/auth/register');
        }
        exit;
    }

    public function logout(): void {
        session_destroy();
        header('Location: ' . APP_URL . '/auth/login');
        exit;
    }
}
