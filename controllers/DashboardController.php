<?php
// ============================================================
// CONTROLADOR: Dashboard
// ============================================================
require_once APP_ROOT . '/models/Venta.php';
require_once APP_ROOT . '/models/Gasto.php';
require_once APP_ROOT . '/models/Producto.php';
require_once APP_ROOT . '/models/Deuda.php';

class DashboardController {
    public function index(): void {
        $ventaModel   = new Venta();
        $gastoModel   = new Gasto();
        $productoModel = new Producto();
        $deudaModel   = new Deuda();

        $hoy       = date('Y-m-d');
        $mes_ini   = date('Y-m-01');
        $mes_fin   = date('Y-m-d');
        $anio_ini  = date('Y-01-01');

        // Totales del mes actual
        $totalVentasMes  = $ventaModel->totalPorPeriodo($mes_ini, $mes_fin);
        $totalGastosMes  = $gastoModel->totalPorPeriodo($mes_ini, $mes_fin);
        $gananciaNeta    = $totalVentasMes - $totalGastosMes;

        // Totales de hoy
        $ventasHoy  = $ventaModel->totalPorPeriodo($hoy, $hoy);
        $gastosHoy  = $gastoModel->totalPorPeriodo($hoy, $hoy);

        // Inventario
        $bajoStock    = $productoModel->bajoStock();
        $masVendidos  = $productoModel->masVendidos(5);

        // Deudas
        $totalDeudas = $deudaModel->totalPendiente();
        $deudas      = $deudaModel->pendientes();

        // Ventas últimos 7 días para gráfico
        $ventasMes = $ventaModel->ventasPorDia((int)date('m'), (int)date('Y'));

        // Balances por Método de Pago (Mes Actual)
        $efectivoVentas = $ventaModel->totalPorMetodo($mes_ini, $mes_fin, 'efectivo');
        $efectivoAbonos = $deudaModel->totalAbonosPorMetodo($mes_ini, $mes_fin, 'efectivo');
        $totalEfectivo  = $efectivoVentas + $efectivoAbonos;

        $bancoVentas = $ventaModel->totalPorMetodo($mes_ini, $mes_fin, 'transferencia');
        $bancoAbonos = $deudaModel->totalAbonosPorMetodo($mes_ini, $mes_fin, 'transferencia');
        $totalBanco  = $bancoVentas + $bancoAbonos;

        require_once APP_ROOT . '/views/dashboard/index.php';
    }
}
