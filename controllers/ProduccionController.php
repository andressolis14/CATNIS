<?php
require_once APP_ROOT . '/models/Produccion.php';
require_once APP_ROOT . '/models/Receta.php';

class ProduccionController {
    private Produccion $model;
    private Receta $recetaModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/auth/login'); exit;
        }
        $this->model       = new Produccion();
        $this->recetaModel = new Receta();
    }

    public function index(): void {
        $producciones = $this->model->todas();
        require_once APP_ROOT . '/views/produccion/index.php';
    }

    public function crear(): void {
        $recetas = $this->recetaModel->todas();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recetaId = (int)($_POST['receta_id'] ?? 0);
            $cantidad = (float)($_POST['cantidad']  ?? 0);

            if (!$recetaId || $cantidad <= 0) {
                $_SESSION['error'] = 'Selecciona una receta y la cantidad producida.';
                header('Location: ' . APP_URL . '/produccion/crear'); exit;
            }

            if ($this->model->registrar(
                $recetaId,
                $cantidad,
                $_SESSION['usuario_id'],
                trim($_POST['notas'] ?? ''),
                $_POST['fecha'] ?? date('Y-m-d')
            )) {
                $_SESSION['exito'] = 'Producción registrada. Stock de insumos descontado.';
            } else {
                $_SESSION['error'] = 'Error al registrar la producción.';
            }
            header('Location: ' . APP_URL . '/produccion'); exit;
        }

        // Preparar datos de recetas con sus insumos para el preview en JS
        $recetasData = [];
        foreach ($recetas as $r) {
            $ins = $this->recetaModel->insumosPorReceta($r['id']);
            $recetasData[$r['id']] = [
                'nombre'      => $r['producto_nombre'],
                'rendimiento' => (float)$r['rendimiento'],
                'insumos'     => array_map(fn($i) => [
                    'id'       => $i['insumo_id'],
                    'nombre'   => $i['insumo_nombre'],
                    'unidad'   => $i['unidad_medida'],
                    'cantidad' => (float)$i['cantidad'],
                    'stock'    => (float)$i['stock_actual'],
                ], $ins),
            ];
        }

        require_once APP_ROOT . '/views/produccion/crear.php';
    }
}
