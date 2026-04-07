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
        $mes  = (int)($_GET['mes']  ?? date('m'));
        $anio = (int)($_GET['anio'] ?? date('Y'));

        $mesIni = sprintf('%04d-%02d-01', $anio, $mes);
        $mesFin = date('Y-m-t', strtotime($mesIni));

        $totalVentas = $this->ventaModel->totalPorPeriodo($mesIni, $mesFin);
        $totalGastos = $this->gastoModel->totalPorPeriodo($mesIni, $mesFin);
        $gananciaNeta = $totalVentas - $totalGastos;

        $ventasPorDia     = $this->ventaModel->ventasPorDia($mes, $anio);
        $gastosPorCategoria = $this->gastoModel->gastosPorCategoria();

        require_once APP_ROOT . '/views/reportes/index.php';
    }
}
