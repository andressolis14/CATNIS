<?php
require_once APP_ROOT . '/models/Receta.php';
require_once APP_ROOT . '/models/Insumo.php';
require_once APP_ROOT . '/models/Producto.php';

class RecetaController {
    private Receta  $model;
    private Insumo  $insumoModel;
    private Producto $productoModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/auth/login'); exit;
        }
        $this->model         = new Receta();
        $this->insumoModel   = new Insumo();
        $this->productoModel = new Producto();
    }

    public function index(): void {
        $recetas = $this->model->todas();
        require_once APP_ROOT . '/views/recetas/index.php';
    }

    public function crear(): void {
        $productos = $this->productoModel->todos();
        $insumos   = $this->insumoModel->todosParaSelect();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productoId = (int)($_POST['producto_id'] ?? 0);
            if (!$productoId) {
                $_SESSION['error'] = 'Debes seleccionar un producto.';
                header('Location: ' . APP_URL . '/recetas/crear'); exit;
            }

            $lineas = $_POST['insumos'] ?? [];
            $insumosProcesados = [];
            foreach ($lineas as $linea) {
                $iid  = (int)($linea['insumo_id'] ?? 0);
                $cant = (float)($linea['cantidad'] ?? 0);
                if ($iid > 0 && $cant > 0) {
                    $insumosProcesados[] = ['insumo_id' => $iid, 'cantidad' => $cant];
                }
            }
            if (empty($insumosProcesados)) {
                $_SESSION['error'] = 'Agrega al menos un insumo a la receta.';
                header('Location: ' . APP_URL . '/recetas/crear'); exit;
            }

            $datos = [
                ':producto_id' => $productoId,
                ':nombre'      => trim($_POST['nombre'] ?? ''),
                ':descripcion' => trim($_POST['descripcion'] ?? ''),
                ':rendimiento' => (float)($_POST['rendimiento'] ?? 1),
            ];

            if ($this->model->crear($datos, $insumosProcesados)) {
                $_SESSION['exito'] = 'Receta creada correctamente.';
                header('Location: ' . APP_URL . '/recetas');
            } else {
                $_SESSION['error'] = 'Error al crear la receta.';
                header('Location: ' . APP_URL . '/recetas/crear');
            }
            exit;
        }
        require_once APP_ROOT . '/views/recetas/form.php';
    }

    public function editar(): void {
        $id     = (int)($_GET['id'] ?? 0);
        $receta = $this->model->buscarPorId($id);
        if (!$receta) {
            $_SESSION['error'] = 'Receta no encontrada.';
            header('Location: ' . APP_URL . '/recetas'); exit;
        }

        $productos = $this->productoModel->todos();
        $insumos   = $this->insumoModel->todosParaSelect();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lineas = $_POST['insumos'] ?? [];
            $insumosProcesados = [];
            foreach ($lineas as $linea) {
                $iid  = (int)($linea['insumo_id'] ?? 0);
                $cant = (float)($linea['cantidad'] ?? 0);
                if ($iid > 0 && $cant > 0) {
                    $insumosProcesados[] = ['insumo_id' => $iid, 'cantidad' => $cant];
                }
            }

            $datos = [
                ':nombre'      => trim($_POST['nombre'] ?? ''),
                ':descripcion' => trim($_POST['descripcion'] ?? ''),
                ':rendimiento' => (float)($_POST['rendimiento'] ?? 1),
            ];

            if ($this->model->actualizar($id, $datos, $insumosProcesados)) {
                $_SESSION['exito'] = 'Receta actualizada correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar la receta.';
            }
            header('Location: ' . APP_URL . '/recetas'); exit;
        }
        require_once APP_ROOT . '/views/recetas/form.php';
    }

    public function eliminar(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($this->model->eliminar($id)) {
            $_SESSION['exito'] = 'Receta eliminada.';
        } else {
            $_SESSION['error'] = 'Error al eliminar.';
        }
        header('Location: ' . APP_URL . '/recetas'); exit;
    }
}
