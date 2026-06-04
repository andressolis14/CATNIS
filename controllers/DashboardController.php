<?php
// ============================================================
// CONTROLADOR: Dashboard
// ============================================================
require_once APP_ROOT . '/models/Venta.php';
require_once APP_ROOT . '/models/Gasto.php';
require_once APP_ROOT . '/models/Producto.php';
require_once APP_ROOT . '/models/Deuda.php';
require_once APP_ROOT . '/models/Caja.php';

class DashboardController
{
    public function index(): void
    {
        $ventaModel = new Venta();
        $gastoModel = new Gasto();
        $productoModel = new Producto();
        $deudaModel = new Deuda();
        $cajaModel = new Caja();

        $mes_ini = date('Y-m-01');
        $mes_fin = date('Y-m-d');
        $hoy = date('Y-m-d');

        // 1. Filtros VENTAS
        $v_ini = $_GET['v_ini'] ?? $mes_ini;
        $v_fin = $_GET['v_fin'] ?? $mes_fin;
        $totalVentasTerm = $ventaModel->totalPorPeriodo($v_ini, $v_fin);

        // 2. Filtros GASTOS
        $g_ini = $_GET['g_ini'] ?? $mes_ini;
        $g_fin = $_GET['g_fin'] ?? $mes_fin;
        $totalGastosTerm = $gastoModel->totalPorPeriodo($g_ini, $g_fin);

        // 3. Filtros GANANCIA NETA (Independiente)
        $gn_ini = $_GET['gn_ini'] ?? $mes_ini;
        $gn_fin = $_GET['gn_fin'] ?? $mes_fin;
        $totalVentasGN = $ventaModel->totalPorPeriodo($gn_ini, $gn_fin);
        $totalGastosGN = $gastoModel->totalPorPeriodo($gn_ini, $gn_fin);
        $gananciaNetaTerm = $totalVentasGN - $totalGastosGN;

        // 4. Filtros POR COBRAR (Basado en fecha de creación de deuda)
        $pc_ini = $_GET['pc_ini'] ?? '2020-01-01'; // Default amplio para deudas
        $pc_fin = $_GET['pc_fin'] ?? $mes_fin;
        // Nota: totalDeudas suele ser el estado actual, pero lo filtramos por creación si se desea
        $totalDeudasTerm = $deudaModel->totalPendienteRango($pc_ini, $pc_fin);
        $deudasTerm = $deudaModel->pendientesRango($pc_ini, $pc_fin);

        // 5. EFECTIVO — acumulado total (sin filtro de fecha)
        $ef_ini = '2020-01-01';
        $ef_fin = $hoy;
        $ef_v = $ventaModel->totalPorMetodo($ef_ini, $ef_fin, 'efectivo');
        $ef_a = $deudaModel->totalAbonosPorMetodo($ef_ini, $ef_fin, 'efectivo');
        $ef_c = $cajaModel->calcularSaldoNetoMetodoRango('efectivo', $ef_ini, $ef_fin);
        $ef_g = $gastoModel->totalPorMetodoPago('efectivo', $ef_ini, $ef_fin);
        $totalEfectivoTerm = $ef_v + $ef_a + $ef_c - $ef_g;

        // 6. BANCO — acumulado total (sin filtro de fecha)
        $ba_ini = '2020-01-01';
        $ba_fin = $hoy;
        $ba_v = $ventaModel->totalPorMetodo($ba_ini, $ba_fin, 'transferencia');
        $ba_a = $deudaModel->totalAbonosPorMetodo($ba_ini, $ba_fin, 'transferencia');
        $ba_c = $cajaModel->calcularSaldoNetoMetodoRango('transferencia', $ba_ini, $ba_fin);
        $ba_g = $gastoModel->totalPorMetodoPago('transferencia', $ba_ini, $ba_fin);
        $totalBancoTerm = $ba_v + $ba_a + $ba_c - $ba_g;

        // Filtros para la GRÁFICA (Mantiene su independencia)
        $chart_inicio = $_GET['chart_inicio'] ?? $mes_ini;
        $chart_fin = $_GET['chart_fin'] ?? $mes_fin;
        $ventasAgrupadas = $ventaModel->ventasPorPeriodoAgrupadas($chart_inicio, $chart_fin);

        // Referencias constantes (Hoy)
        $ventasHoy = $ventaModel->totalPorPeriodo($hoy, $hoy);
        $gastosHoy = $gastoModel->totalPorPeriodo($hoy, $hoy);

        // Otros datos
        $bajoStock = $productoModel->bajoStock();
        $masVendidos = $productoModel->masVendidos(5);

        require_once APP_ROOT . '/views/dashboard/index.php';
    }
}
