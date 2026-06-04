<?php
// ============================================================
// CONTROLADOR: Gastos
// ============================================================
require_once APP_ROOT . '/models/Gasto.php';
require_once APP_ROOT . '/models/ItemGasto.php';

class GastoController {
    private Gasto $model;
    private ItemGasto $itemModel;

    public function __construct() {
        $this->model = new Gasto();
        $this->itemModel = new ItemGasto();
    }

    public function index(): void {
        $filtros = [
            'categoria'    => $_GET['categoria'] ?? '',
            'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
            'fecha_fin'    => $_GET['fecha_fin'] ?? '',
        ];
        $gastos = $this->model->todos(array_filter($filtros));
        
        // Cargar detalles para cada gasto (para el preview en la tabla)
        foreach ($gastos as $i => $g) {
            $gastos[$i]['detalles'] = $this->model->detallesPorGasto($g['id']);
        }

        require_once APP_ROOT . '/views/gastos/index.php';
    }

    public function crear(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = $_POST['items'] ?? [];

            if (empty($_POST['categoria'])) {
                $_SESSION['error'] = 'Debes seleccionar una categoría.';
                header('Location: ' . APP_URL . '/gastos/crear');
                exit;
            }

            if (empty($items)) {
                $_SESSION['error'] = 'Debes agregar al menos un ítem al gasto.';
                header('Location: ' . APP_URL . '/gastos/crear');
                exit;
            }

            $total = 0;
            $itemsProcesados = [];
            foreach ($items as $item) {
                $montoItem    = (float)($item['monto'] ?? 0);
                $cantidadItem = (float)($item['cantidad'] ?? 1);
                if ($montoItem > 0 && $cantidadItem > 0) {
                    $total += ($montoItem * $cantidadItem);

                    // Resolver el ID del maestro (buscar o crear)
                    $descItem = trim($item['descripcion'] ?? 'Sin descripción');
                    $itemMaestroId = $this->itemModel->obtenerOcrear($descItem);

                    $itemsProcesados[] = [
                        'descripcion'      => $descItem,
                        'unidad_medida'    => trim($item['unidad_medida'] ?? 'unid') ?: 'unid',
                        'cantidad'         => $cantidadItem,
                        'monto'            => $montoItem,
                        'item_maestro_id'  => $itemMaestroId
                    ];
                }
            }

            $numeroFactura = trim($_POST['numero_factura'] ?? '');
            if ($numeroFactura !== '' && $this->model->existeFactura($numeroFactura)) {
                $_SESSION['error'] = "Ya existe un gasto registrado con el N° de factura \"$numeroFactura\". Verifica antes de continuar.";
                header('Location: ' . APP_URL . '/gastos/crear');
                exit;
            }

            $datosGasto = [
                ':usuario_id'     => $_SESSION['usuario_id'],
                ':numero_factura' => $numeroFactura,
                ':monto'          => $total,
                ':categoria'      => $_POST['categoria'] ?? 'otros',
                ':fecha'          => $_POST['fecha'] ?? date('Y-m-d'),
                ':descripcion'    => trim($_POST['descripcion_general'] ?? ''),
                ':metodo_pago'    => $_POST['metodo_pago'] ?? 'efectivo',
                ':proveedor'      => trim($_POST['proveedor'] ?? '') ?: null,
            ];

            if ($this->model->crear($datosGasto, $itemsProcesados)) {
                $_SESSION['exito'] = 'Gasto registrado correctamente. Total: $' . number_format($total, 0, ',', '.');
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
                $cantidadItem = (float)($item['cantidad'] ?? 1);
                if ($montoItem > 0 && $cantidadItem > 0) {
                    $total += ($montoItem * $cantidadItem);

                    // Resolver el ID del maestro (buscar o crear)
                    $descItem = trim($item['descripcion'] ?? 'Sin descripción');
                    $itemMaestroId = $this->itemModel->obtenerOcrear($descItem);

                    $itemsProcesados[] = [
                        'descripcion'      => $descItem,
                        'unidad_medida'    => trim($item['unidad_medida'] ?? 'unid') ?: 'unid',
                        'cantidad'         => $cantidadItem,
                        'monto'            => $montoItem,
                        'item_maestro_id'  => $itemMaestroId
                    ];
                }
            }

            $datosGasto = [
                ':numero_factura' => trim($_POST['numero_factura'] ?? ''),
                ':monto'          => $total,
                ':categoria'      => $_POST['categoria'] ?? 'otros',
                ':fecha'          => $_POST['fecha'] ?? date('Y-m-d'),
                ':descripcion'    => trim($_POST['descripcion_general'] ?? ''),
                ':metodo_pago'    => $_POST['metodo_pago'] ?? 'efectivo',
                ':proveedor'      => trim($_POST['proveedor'] ?? '') ?: null,
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
            $_SESSION['error'] = 'Error al eliminar.';
        }
        header('Location: ' . APP_URL . '/gastos');
        exit;
    }

    public function activos(): void {
        $activos = $this->model->todos(['categoria' => 'activos']);
        foreach ($activos as $i => $a) {
            $activos[$i]['detalles'] = $this->model->detallesPorGasto($a['id']);
        }
        $totalActivos = array_sum(array_column($activos, 'monto'));
        require_once APP_ROOT . '/views/gastos/activos.php';
    }

    public function buscarItems(): void {
        header('Content-Type: application/json');
        echo json_encode($this->itemModel->todos());
        exit;
    }

    public function maestro(): void {
        $items = $this->itemModel->todos();
        require_once APP_ROOT . '/views/gastos/items_maestro.php';
    }

    public function crearItem(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $unidad = trim($_POST['unidad_medida'] ?? 'unid');
            
            if ($this->itemModel->crear($nombre, null, $unidad)) {
                $_SESSION['exito'] = 'Ítem creado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al crear el ítem.';
            }
        }
        header('Location: ' . APP_URL . '/gastos/maestro');
        exit;
    }

    public function editarItem(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id     = (int)$_POST['id'];
            $nombre = trim($_POST['nombre']);
            $codigo = trim($_POST['codigo']);
            $unidad = trim($_POST['unidad_medida'] ?? 'unid');
            
            if ($this->itemModel->actualizar($id, $nombre, $codigo, $unidad)) {
                $_SESSION['exito'] = 'Ítem actualizado.';
            } else {
                $_SESSION['error'] = 'Error al actualizar el ítem.';
            }
        }
        header('Location: ' . APP_URL . '/gastos/maestro');
        exit;
    }

    public function eliminarItem(): void {
        $id = (int)($_GET['id'] ?? 0);
        try {
            if ($this->itemModel->eliminar($id)) {
                $_SESSION['exito'] = 'Ítem eliminado.';
            } else {
                $_SESSION['error'] = 'Error al eliminar.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'No se puede eliminar un ítem que ya ha sido usado en gastos.';
        }
        header('Location: ' . APP_URL . '/gastos/maestro');
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
