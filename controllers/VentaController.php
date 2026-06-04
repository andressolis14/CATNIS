<?php
// ============================================================
// CONTROLADOR: Ventas
// ============================================================
require_once APP_ROOT . '/models/Venta.php';
require_once APP_ROOT . '/models/Producto.php';
require_once APP_ROOT . '/models/Cliente.php';
require_once APP_ROOT . '/models/Caja.php';

class VentaController
{
    private Venta $model;
    private Producto $productoModel;
    private Cliente $clienteModel;

    public function __construct()
    {
        $this->model = new Venta();
        $this->productoModel = new Producto();
        $this->clienteModel = new Cliente();
    }

    public function index(): void
    {
        $ventas = $this->model->todas();
        require_once APP_ROOT . '/views/ventas/index.php';
    }

    public function crear(): void
    {
        $modo = $_GET['modo'] ?? $_POST['modo'] ?? 'local';
        $cajaModel = new Caja();
        if ($modo !== 'externo' && !$cajaModel->obtenerSesionActiva()) {
            $_SESSION['error'] = 'DEBES ABRIR CAJA PARA PODER VENDER. Por favor, abre el turno primero.';
            header('Location: ' . APP_URL . '/caja');
            exit;
        }

        $productos = $this->productoModel->todos();
        $clientes = $this->clienteModel->todos();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = $_POST['tipo'] ?? 'contado';
            $clienteId = !empty($_POST['cliente_id']) ? (int) $_POST['cliente_id'] : null;
            $items = $_POST['items'] ?? [];

            if (empty($items)) {
                $_SESSION['error'] = 'Debes agregar al menos un producto.';
                header('Location: ' . APP_URL . '/ventas/crear');
                exit;
            }

            $total = 0;
            $detalles = [];
            foreach ($items as $item) {
                $prod = $this->productoModel->buscarPorId((int) $item['producto_id']);
                $esServicio = ($prod['tipo'] ?? 'producto') === 'servicio';
                if (!$prod || (!$esServicio && $prod['stock'] < (int) $item['cantidad'])) {
                    $_SESSION['error'] = "Stock insuficiente para: " . ($prod['nombre'] ?? 'producto');
                    header('Location: ' . APP_URL . '/ventas/crear');
                    exit;
                }
                $precioUnitario = isset($item['precio_unitario']) && (float)$item['precio_unitario'] > 0
                    ? (float)$item['precio_unitario']
                    : $prod['precio_venta'];
                $subtotal = $precioUnitario * (int) $item['cantidad'];
                $total += $subtotal;
                $detalles[] = [
                    'producto_id' => $prod['id'],
                    'tipo' => $prod['tipo'] ?? 'producto',
                    'cantidad' => (int) $item['cantidad'],
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotal,
                ];
            }

            $ventaData = [
                ':usuario_id' => $_SESSION['usuario_id'],
                ':cliente_id' => $clienteId,
                ':tipo' => $tipo,
                ':total' => $total,
                ':metodo_pago' => $_POST['metodo_pago'] ?? 'efectivo',
                ':estado' => 'completada',
                ':notas' => trim($_POST['notas'] ?? ''),
                ':fecha' => $_POST['fecha'] . ' ' . date('H:i:s'), // Mantiene la hora actual
            ];

            $ventaId = $this->model->crear($ventaData, $detalles);

            if ($ventaId) {
                $_SESSION['exito'] = 'Venta registrada correctamente. Total: $' . number_format($total, 2);
                header('Location: ' . APP_URL . '/ventas/detalle?id=' . $ventaId);
            } else {
                $_SESSION['error'] = 'Error al registrar la venta.';
                header('Location: ' . APP_URL . '/ventas/crear');
            }
            exit;
        }

        require_once APP_ROOT . '/views/ventas/crear.php';
    }

    public function detalle(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $venta = $this->model->buscarPorId($id);
        if (!$venta) {
            header('Location: ' . APP_URL . '/ventas');
            exit;
        }
        $detalles = $this->model->detallesPorVenta($id);

        // Preparar texto para WhatsApp
        $txt = "🛒 *Recibo de Compra - " . APP_NAME . "*\n";
        $txt .= "Venta #V-" . str_pad($venta['id'], 5, '0', STR_PAD_LEFT) . "\n";
        $txt .= "Fecha: " . date('d/m/Y H:i', strtotime($venta['fecha'])) . "\n";
        $txt .= "---------------------------\n";
        foreach ($detalles as $d) {
            $txt .= "- " . $d['cantidad'] . "x " . $d['producto'] . ": $" . number_format($d['subtotal'], 2) . "\n";
        }
        $txt .= "---------------------------\n";
        $txt .= "*TOTAL: $" . number_format($venta['total'], 2) . "*\n";
        $txt .= "Tipo: " . ucfirst($venta['tipo']) . "\n\n";
        $txt .= "¡Gracias por preferir Catnis Bakery! 🥐";

        $waLink = "https://wa.me/?text=" . urlencode($txt);

        require_once APP_ROOT . '/views/ventas/detalle.php';
    }

    public function recibo(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $venta = $this->model->buscarPorId($id);
        if (!$venta) {
            header('Location: ' . APP_URL . '/ventas');
            exit;
        }
        $detalles = $this->model->detallesPorVenta($id);
        require_once APP_ROOT . '/views/ventas/recibo.php';
    }

    public function editar(): void
    {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'Solo los administradores pueden editar ventas.';
            header('Location: ' . APP_URL . '/ventas');
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        $venta = $this->model->buscarPorId($id);

        if (!$venta) {
            $_SESSION['error'] = 'Venta no encontrada.';
            header('Location: ' . APP_URL . '/ventas');
            exit;
        }

        $productos = $this->productoModel->todos();
        $clientes = $this->clienteModel->todos();
        $detalles = $this->model->detallesPorVenta($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = $_POST['tipo'] ?? 'contado';
            $clienteId = !empty($_POST['cliente_id']) ? (int) $_POST['cliente_id'] : null;
            $items = $_POST['items'] ?? [];

            if (empty($items)) {
                $_SESSION['error'] = 'Debes agregar al menos un producto.';
                header('Location: ' . APP_URL . '/ventas/editar?id=' . $id);
                exit;
            }

            $total = 0;
            $detallesNuevos = [];
            foreach ($items as $item) {
                $prod = $this->productoModel->buscarPorId((int) $item['producto_id']);
                $precioUnitario = isset($item['precio_unitario']) && (float)$item['precio_unitario'] > 0
                    ? (float)$item['precio_unitario']
                    : $prod['precio_venta'];
                $subtotal = $precioUnitario * (int) $item['cantidad'];
                $total += $subtotal;
                $detallesNuevos[] = [
                    'producto_id'    => $prod['id'],
                    'tipo'           => $prod['tipo'] ?? 'producto',
                    'cantidad'       => (int) $item['cantidad'],
                    'precio_unitario'=> $precioUnitario,
                    'subtotal'       => $subtotal,
                ];
            }

            $ventaData = [
                ':cliente_id' => $clienteId,
                ':tipo' => $tipo,
                ':total' => $total,
                ':metodo_pago' => $_POST['metodo_pago'] ?? 'efectivo',
                ':notas' => trim($_POST['notas'] ?? ''),
                ':fecha' => $_POST['fecha'] . ' ' . date('H:i:s'),
            ];

            $ok = $this->model->actualizar($id, $ventaData, $detallesNuevos);

            if ($ok) {
                $_SESSION['exito'] = 'Venta #' . $id . ' actualizada correctamente.';
                header('Location: ' . APP_URL . '/ventas/detalle?id=' . $id);
            } else {
                $_SESSION['error'] = 'Error al actualizar la venta (posible falta de stock). ' . ($_SESSION['error_db'] ?? '');
                header('Location: ' . APP_URL . '/ventas/editar?id=' . $id);
            }
            exit;
        }

        require_once APP_ROOT . '/views/ventas/editar.php';
    }
}
