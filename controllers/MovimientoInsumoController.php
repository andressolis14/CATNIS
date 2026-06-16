<?php
require_once APP_ROOT . '/models/MovimientoInsumo.php';
require_once APP_ROOT . '/models/Insumo.php';

class MovimientoInsumoController {
    private MovimientoInsumo $model;
    private Insumo $insumoModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/auth/login'); exit;
        }
        $this->model       = new MovimientoInsumo();
        $this->insumoModel = new Insumo();
    }

    public function index(): void {
        $filtros = [
            'insumo_id'    => $_GET['insumo_id']    ?? '',
            'tipo'         => $_GET['tipo']          ?? '',
            'fecha_inicio' => $_GET['fecha_inicio']  ?? '',
            'fecha_fin'    => $_GET['fecha_fin']     ?? '',
        ];
        $movimientos = $this->model->todos(array_filter($filtros));
        $insumos     = $this->insumoModel->todos();
        require_once APP_ROOT . '/views/movimientos/index.php';
    }

    public function crear(): void {
        $insumos = $this->insumoModel->todos();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $insumoId = (int)($_POST['insumo_id'] ?? 0);
            $cantidad = (float)($_POST['cantidad']  ?? 0);

            if (!$insumoId || $cantidad <= 0) {
                $_SESSION['error'] = 'Selecciona un insumo y una cantidad válida.';
                header('Location: ' . APP_URL . '/movimientos/crear'); exit;
            }

            $datos = [
                ':insumo_id'   => $insumoId,
                ':tipo'        => $_POST['tipo']        ?? 'entrada',
                ':cantidad'    => $cantidad,
                ':descripcion' => trim($_POST['descripcion'] ?? ''),
                ':usuario_id'  => $_SESSION['usuario_id'],
                ':fecha'       => $_POST['fecha']       ?? date('Y-m-d'),
            ];

            if ($this->model->registrar($datos)) {
                $_SESSION['exito'] = 'Movimiento registrado. Stock actualizado.';
            } else {
                $_SESSION['error'] = 'Error al registrar el movimiento.';
            }
            header('Location: ' . APP_URL . '/movimientos'); exit;
        }

        $insumoSeleccionado = (int)($_GET['insumo_id'] ?? 0);
        require_once APP_ROOT . '/views/movimientos/form.php';
    }
}
