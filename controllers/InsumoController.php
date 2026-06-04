<?php
require_once APP_ROOT . '/models/Insumo.php';

class InsumoController {
    private Insumo $model;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/auth/login'); exit;
        }
        $this->model = new Insumo();
    }

    public function index(): void {
        $insumos   = $this->model->todos();
        $bajoStock = $this->model->bajoStock();
        require_once APP_ROOT . '/views/insumos/index.php';
    }

    public function crear(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                ':nombre'         => trim($_POST['nombre'] ?? ''),
                ':descripcion'    => trim($_POST['descripcion'] ?? ''),
                ':unidad_medida'  => trim($_POST['unidad_medida'] ?? 'unid'),
                ':stock'          => (float)($_POST['stock'] ?? 0),
                ':stock_minimo'   => (float)($_POST['stock_minimo'] ?? 0),
                ':costo_unitario' => (float)str_replace(['.', ','], ['', '.'], $_POST['costo_unitario'] ?? '0'),
            ];

            if (empty($datos[':nombre'])) {
                $_SESSION['error'] = 'El nombre es obligatorio.';
                header('Location: ' . APP_URL . '/insumos/crear'); exit;
            }

            if ($this->model->crear($datos)) {
                $_SESSION['exito'] = 'Insumo creado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al crear el insumo.';
            }
            header('Location: ' . APP_URL . '/insumos'); exit;
        }
        require_once APP_ROOT . '/views/insumos/form.php';
    }

    public function editar(): void {
        $id     = (int)($_GET['id'] ?? 0);
        $insumo = $this->model->buscarPorId($id);
        if (!$insumo) {
            $_SESSION['error'] = 'Insumo no encontrado.';
            header('Location: ' . APP_URL . '/insumos'); exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                ':nombre'         => trim($_POST['nombre'] ?? ''),
                ':descripcion'    => trim($_POST['descripcion'] ?? ''),
                ':unidad_medida'  => trim($_POST['unidad_medida'] ?? 'unid'),
                ':stock'          => (float)($_POST['stock'] ?? 0),
                ':stock_minimo'   => (float)($_POST['stock_minimo'] ?? 0),
                ':costo_unitario' => (float)str_replace(['.', ','], ['', '.'], $_POST['costo_unitario'] ?? '0'),
            ];

            if ($this->model->actualizar($id, $datos)) {
                $_SESSION['exito'] = 'Insumo actualizado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar.';
            }
            header('Location: ' . APP_URL . '/insumos'); exit;
        }
        require_once APP_ROOT . '/views/insumos/form.php';
    }

    public function eliminar(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($this->model->eliminar($id)) {
            $_SESSION['exito'] = 'Insumo eliminado.';
        } else {
            $_SESSION['error'] = 'Error al eliminar.';
        }
        header('Location: ' . APP_URL . '/insumos'); exit;
    }
}
