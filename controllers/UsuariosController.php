<?php
// ===================================
// CONTROLADOR: Gestión de Usuarios
// ===================================
require_once APP_ROOT . '/models/Usuario.php';

class UsuariosController
{
    private Usuario $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario();

        // Verificar sesión y rol de admin en el constructor para proteger todo el controlador
        if (!isset($_SESSION['usuario_id']) || trim(strtolower($_SESSION['rol'] ?? '')) !== 'admin') {
            $_SESSION['error'] = "Acceso denegado. Se requiere ser administrador.";
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }
    }

    /**
     * Muestra la página de gestión: lista de usuarios + formulario de registro
     */
    public function index(): void
    {
        $pageTitle = 'Gestión de Usuarios';
        $usuarios = $this->usuario->listarTodos();
        require_once APP_ROOT . '/views/auth/register.php';
    }

    /**
     * Procesa la creación de un nuevo usuario
     */
    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/usuarios');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        $rol = $_POST['rol'] ?? 'usuario';

        if (empty($nombre) || empty($correo) || empty($contrasena)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            header('Location: ' . APP_URL . '/usuarios');
            exit;
        }

        if ($this->usuario->correoExiste($correo)) {
            $_SESSION['error'] = 'El correo ya está registrado.';
            header('Location: ' . APP_URL . '/usuarios');
            exit;
        }

        if ($this->usuario->crear(['nombre' => $nombre, 'correo' => $correo, 'contrasena' => $contrasena, 'rol' => $rol])) {
            $_SESSION['exito'] = "¡Usuario '$nombre' creado correctamente.";
            header('Location: ' . APP_URL . '/usuarios');
        } else {
            $_SESSION['error'] = 'Error al crear el usuario.';
            header('Location: ' . APP_URL . '/usuarios');
        }
        exit;
    }

    /**
     * Elimina un usuario por ID (no puede eliminarse a sí mismo)
     */
    public function eliminar(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID de usuario no válido.';
            header('Location: ' . APP_URL . '/usuarios');
            exit;
        }

        // No permitir que el admin se elimine a sí mismo
        if ($id === (int) $_SESSION['usuario_id']) {
            $_SESSION['error'] = 'No puedes eliminar tu propia cuenta.';
            header('Location: ' . APP_URL . '/usuarios');
            exit;
        }

        if ($this->usuario->eliminar($id)) {
            $_SESSION['exito'] = 'Usuario eliminado correctamente.';
        } else {
            $_SESSION['error'] = 'Error al eliminar el usuario.';
        }
        header('Location: ' . APP_URL . '/usuarios');
        exit;
    }
}
