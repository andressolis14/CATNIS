<?php
// ============================================================
// CONTROLADOR: Reportes
// ============================================================
require_once APP_ROOT . '/models/Venta.php';
require_once APP_ROOT . '/models/Gasto.php';

class ReporteController {
    private Venta $ventaModel;
    private Gasto $gastoModel;

    public function __construct() {
        $this->ventaModel = new Venta();
        $this->gastoModel = new Gasto();
    }

    public function index(): void {
        // Filtros para el Resumen General (Mes/Año)
        $mes  = (int)($_GET['mes']  ?? date('m'));
        $anio = (int)($_GET['anio'] ?? date('Y'));
        $mesIni = sprintf('%04d-%02d-01', $anio, $mes);
        $mesFin = date('Y-m-t', strtotime($mesIni));

        // Filtros para las GRÁFICAS (Rango Personalizado)
        $chart_inicio = $_GET['chart_inicio'] ?? $mesIni;
        $chart_fin    = $_GET['chart_fin']    ?? date('Y-m-d');

        // Totales para el Resumen General (KPIs de arriba)
        $totalVentas = $this->ventaModel->totalPorPeriodo($mesIni, $mesFin);
        $totalGastos = $this->gastoModel->totalPorPeriodo($mesIni, $mesFin);
        $gananciaNeta = $totalVentas - $totalGastos;

        // Totales para las GRÁFICAS (Rango Personalizado)
        $chart_total_ventas  = $this->ventaModel->totalPorPeriodo($chart_inicio, $chart_fin);
        $chart_total_gastos  = $this->gastoModel->totalPorPeriodo($chart_inicio, $chart_fin);
        $chart_ganancia_neta = $chart_total_ventas - $chart_total_gastos;

        $ventasAgrupadas    = $this->ventaModel->ventasPorPeriodoAgrupadas($chart_inicio, $chart_fin);
        $gastosPorCategoria = $this->gastoModel->gastosPorCategoria($chart_inicio, $chart_fin);
        
        // Para compatibilidad con la tabla del PDF
        $ventasPorDia = $ventasAgrupadas; 

        require_once APP_ROOT . '/views/reportes/index.php';
    }

    public function items(): void {
        $fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fecha_fin    = $_GET['fecha_fin']    ?? date('Y-m-d');
        require_once APP_ROOT . '/views/reportes/items.php';
    }
}
