<?php
// ============================================================
// CONTROLADOR: Gastos
// ============================================================
require_once APP_ROOT . '/models/Gasto.php';

class GastoController {
    private Gasto $model;

    public function __construct() {
        $this->model = new Gasto();
    }

    public function index(): void {
        $filtros = [
            'categoria'    => $_GET['categoria'] ?? '',
            'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
            'fecha_fin'    => $_GET['fecha_fin'] ?? '',
        ];
        $gastos = $this->model->todos(array_filter($filtros));
        
        // Cargar detalles para cada gasto (para el preview en la tabla)
        foreach ($gastos as &$g) {
            $g['detalles'] = $this->model->detallesPorGasto($g['id']);
        }

        require_once APP_ROOT . '/views/gastos/index.php';
    }

    public function crear(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = $_POST['items'] ?? [];
            
            if (empty($items)) {
                $_SESSION['error'] = 'Debes agregar al menos un ítem al gasto.';
                header('Location: ' . APP_URL . '/gastos/crear');
                exit;
            }

            $total = 0;
            $itemsProcesados = [];
            foreach ($items as $item) {
                $montoItem    = (float)($item['monto'] ?? 0);
                $cantidadItem = (int)($item['cantidad'] ?? 1);
                if ($montoItem > 0 && $cantidadItem > 0) {
                    $total += ($montoItem * $cantidadItem);
                    $itemsProcesados[] = [
                        'descripcion' => trim($item['descripcion'] ?? 'Sin descripción'),
                        'cantidad'    => $cantidadItem,
                        'monto'       => $montoItem
                    ];
                }
            }

            $datosGasto = [
                ':usuario_id'     => $_SESSION['usuario_id'],
                ':numero_factura' => trim($_POST['numero_factura'] ?? ''),
                ':monto'          => $total,
                ':categoria'      => $_POST['categoria'] ?? 'otros',
                ':fecha'          => $_POST['fecha'] ?? date('Y-m-d'),
                ':descripcion'    => trim($_POST['descripcion_general'] ?? ''), // Resumen opcional
            ];

            if ($this->model->crear($datosGasto, $itemsProcesados)) {
                $_SESSION['exito'] = 'Gasto registrado correctamente. Total: $' . number_format($total, 2);
            } else {
                $_SESSION['error'] = 'Error al registrar el gasto.';
            }
            header('Location: ' . APP_URL . '/gastos');
            exit;
        }
        require_once APP_ROOT . '/views/gastos/form.php';
    }

    public function editar(): void {
        $id    = (int)($_GET['id'] ?? 0);
        $gasto = $this->model->buscarPorId($id);
        if (!$gasto) {
            $_SESSION['error'] = 'Gasto no encontrado.';
            header('Location: ' . APP_URL . '/gastos');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = $_POST['items'] ?? [];
            $total = 0;
            $itemsProcesados = [];
            foreach ($items as $item) {
                $montoItem    = (float)($item['monto'] ?? 0);
                $cantidadItem = (int)($item['cantidad'] ?? 1);
                if ($montoItem > 0 && $cantidadItem > 0) {
                    $total += ($montoItem * $cantidadItem);
                    $itemsProcesados[] = [
                        'descripcion' => trim($item['descripcion'] ?? 'Sin descripción'),
                        'cantidad'    => $cantidadItem,
                        'monto'       => $montoItem
                    ];
                }
            }

            $datosGasto = [
                ':numero_factura' => trim($_POST['numero_factura'] ?? ''),
                ':monto'          => $total,
                ':categoria'      => $_POST['categoria'] ?? 'otros',
                ':fecha'          => $_POST['fecha'] ?? date('Y-m-d'),
                ':descripcion'    => trim($_POST['descripcion_general'] ?? ''),
            ];

            if ($this->model->actualizar($id, $datosGasto, $itemsProcesados)) {
                $_SESSION['exito'] = 'Gasto actualizado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar.';
            }
            header('Location: ' . APP_URL . '/gastos');
            exit;
        }
        require_once APP_ROOT . '/views/gastos/form.php';
    }

    public function eliminar(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($this->model->eliminar($id)) {
            $_SESSION['exito'] = 'Gasto eliminado.';
        } else {
            $_SESSION['error'] = 'No se pudo eliminar.';
        }
        header('Location: ' . APP_URL . '/gastos');
        exit;
    }

    public function detalle(): void {
        $id    = (int)($_GET['id'] ?? 0);
        $gasto = $this->model->buscarPorId($id);
        if (!$gasto) {
            $_SESSION['error'] = 'Gasto no encontrado.';
            header('Location: ' . APP_URL . '/gastos');
            exit;
        }
        require_once APP_ROOT . '/views/gastos/detalle.php';
    }
}
