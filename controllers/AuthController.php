<?php
// ============================================================
// CONTROLADOR: Autenticación
// ============================================================
require_once APP_ROOT . '/models/Usuario.php';

class AuthController
{
    private Usuario $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    public function login(): void
    {
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }
        require_once APP_ROOT . '/views/auth/login.php';
    }

    public function proceso_login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }
        $correo = trim($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';

        if (empty($correo) || empty($contrasena)) {
            $_SESSION['error'] = 'Por favor completa todos los campos.';
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }

        $user = $this->usuario->buscarPorCorreo($correo);

        if ($user && $this->usuario->verificarContrasena($contrasena, $user['contrasena'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['rol'] = $user['rol'];
            $_SESSION['correo'] = $user['correo'];

            // Mantener sesión activa indefinidamente para el admin
            if ($user['correo'] === 'catnisbakery@gmail.com') {
                setcookie('admin_remember', 'true', time() + (86400 * 365), '/');
            }

            $_SESSION['exito'] = '¡Bienvenido, ' . $user['nombre'] . '!';
            header('Location: ' . APP_URL . '/dashboard');
        } else {
            $_SESSION['error'] = 'Correo o contraseña incorrectos.';
            header('Location: ' . APP_URL . '/auth/login');
        }
        exit;
    }

    public function logout(): void
    {
        setcookie('admin_remember', '', time() - 3600, '/'); // Eliminar cookie si decide cerrar sesión
        session_unset();
        session_destroy();
        header('Location: ' . APP_URL . '/auth/login');
        exit;
    }

    public function olvido(): void
    {
        require_once APP_ROOT . '/views/auth/olvido.php';
    }

    public function proceso_olvido(): void
    {
        $correo = trim($_POST['correo'] ?? '');
        $user = $this->usuario->buscarPorCorreo($correo);

        if (!$user) {
            $_SESSION['error'] = 'No encontramos ninguna cuenta con ese correo.';
            header('Location: ' . APP_URL . '/auth/olvido');
            exit;
        }

        // Generar token único y expiración (1 hora)
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        if ($this->usuario->guardarTokenRecuperacion($correo, $token, $expira)) {
            $enlace = APP_URL . "/auth/restablecer?token=$token";

            // Cuerpo del correo (Básico para mail())
            $asunto = "Restablecer contraseña - " . APP_NAME;
            $mensaje = "Hola " . $user['nombre'] . ",\n\n";
            $mensaje .= "Has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace para continuar:\n\n";
            $mensaje .= $enlace . "\n\n";
            $mensaje .= "Este enlace expirará en 1 hora.\n\n";
            $mensaje .= "Si no solicitaste esto, ignora este correo.";

            $headers = "From: " . APP_NAME . " <" . MAIL_SENDER . ">\r\n";
            $headers .= "Reply-To: " . MAIL_SENDER . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            if (@mail($correo, $asunto, $mensaje, $headers)) {
                $_SESSION['exito'] = 'Te hemos enviado un correo con las instrucciones.';
            } else {
                $_SESSION['error'] = 'No se pudo enviar el correo. Contacta al administrador.';
                // Para depuración en logs
                error_log("Error enviando correo a $correo. Enlace: $enlace");
            }
        }
        header('Location: ' . APP_URL . '/auth/olvido');
        exit;
    }

    public function restablecer(): void
    {
        $token = $_GET['token'] ?? '';
        $user = $this->usuario->buscarPorToken($token);

        if (!$user) {
            $_SESSION['error'] = 'El enlace es inválido o ha expirado.';
            header('Location: ' . APP_URL . '/auth/olvido');
            exit;
        }

        require_once APP_ROOT . '/views/auth/restablecer.php';
    }

    public function proceso_restablecer(): void
    {
        $token = $_POST['token'] ?? '';
        $nueva = $_POST['contrasena'] ?? '';
        $confirm = $_POST['confirmar'] ?? '';

        $user = $this->usuario->buscarPorToken($token);
        if (!$user) {
            $_SESSION['error'] = 'Token inválido.';
            header('Location: ' . APP_URL . '/auth/olvido');
            exit;
        }

        if (empty($nueva) || strlen($nueva) < 6) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres.';
            header('Location: ' . APP_URL . "/auth/restablecer?token=$token");
            exit;
        }

        if ($nueva !== $confirm) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            header('Location: ' . APP_URL . "/auth/restablecer?token=$token");
            exit;
        }

        if ($this->usuario->actualizarContrasena($user['id'], $nueva)) {
            $_SESSION['exito'] = 'Tu contraseña ha sido actualizada. Ya puedes iniciar sesión.';
            header('Location: ' . APP_URL . '/auth/login');
        } else {
            $_SESSION['error'] = 'Error al actualizar la contraseña.';
            header('Location: ' . APP_URL . "/auth/restablecer?token=$token");
        }
        exit;
    }
}
