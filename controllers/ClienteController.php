<?php
// ============================================================
// CONTROLADOR: Clientes
// ============================================================
require_once APP_ROOT . '/models/Cliente.php';

class ClienteController {
    private Cliente $model;

    public function __construct() {
        $this->model = new Cliente();
    }

    public function index(): void {
        $clientes = $this->model->todos();
        require_once APP_ROOT . '/views/clientes/index.php';
    }

    public function crear(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                ':nombre'    => trim($_POST['nombre'] ?? ''),
                ':telefono'  => trim($_POST['telefono'] ?? ''),
                ':correo'    => trim($_POST['correo'] ?? ''),
                ':direccion' => trim($_POST['direccion'] ?? ''),
            ];
            if (empty($datos[':nombre'])) {
                $_SESSION['error'] = 'El nombre es obligatorio.';
            } elseif ($this->model->crear($datos)) {
                $_SESSION['exito'] = 'Cliente creado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al crear el cliente.';
            }
            header('Location: ' . APP_URL . '/clientes');
            exit;
        }
        require_once APP_ROOT . '/views/clientes/form.php';
    }

    public function editar(): void {
        $id = (int)($_GET['id'] ?? 0);
        $cliente = $this->model->buscarPorId($id);
        if (!$cliente) {
            $_SESSION['error'] = 'Cliente no encontrado.';
            header('Location: ' . APP_URL . '/clientes');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                ':nombre'    => trim($_POST['nombre'] ?? ''),
                ':telefono'  => trim($_POST['telefono'] ?? ''),
                ':correo'    => trim($_POST['correo'] ?? ''),
                ':direccion' => trim($_POST['direccion'] ?? ''),
            ];
            if ($this->model->actualizar($id, $datos)) {
                $_SESSION['exito'] = 'Cliente actualizado.';
            } else {
                $_SESSION['error'] = 'Error al actualizar.';
            }
            header('Location: ' . APP_URL . '/clientes');
            exit;
        }
        require_once APP_ROOT . '/views/clientes/form.php';
    }

    public function eliminar(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($this->model->eliminar($id)) {
            $_SESSION['exito'] = 'Cliente eliminado.';
        } else {
            $_SESSION['error'] = 'No se pudo eliminar.';
        }
        header('Location: ' . APP_URL . '/clientes');
        exit;
    }

    public function detalle(): void {
        $id      = (int)($_GET['id'] ?? 0);
        $cliente = $this->model->buscarPorId($id);
        if (!$cliente) {
            header('Location: ' . APP_URL . '/clientes');
            exit;
        }
        $historial = $this->model->historialVentas($id);
        require_once APP_ROOT . '/views/clientes/detalle.php';
    }
}
