<?php
$pageTitle = 'Reportes';
require_once APP_ROOT . '/views/layout/header.php';

$meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
?>

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h4><i class="fas fa-chart-line me-2" style="color:var(--accent)"></i>Reportes</h4>
        <p>Análisis financiero mensual del negocio</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="exportarPDF()" class="btn"
            style="background:rgba(59,130,246,0.15); color:var(--accent-blue); border:1px solid rgba(59,130,246,0.2); border-radius:10px; padding:8px 16px; font-weight:600; display:flex; align-items:center; gap:8px;"
            title="Exportar PDF">
            <i class="fas fa-file-pdf"></i> <span>Exportar PDF</span>
        </button>
        <a href="<?= APP_URL ?>/reportes/items" class="btn"
            style="background:rgba(139,92,246,0.15); color:var(--accent); border:1px solid rgba(139,92,246,0.2); border-radius:10px; padding:8px 16px; font-weight:600; display:flex; align-items:center; gap:8px; text-decoration:none;">
            <i class="fas fa-boxes"></i> <span>Reporte de Ítems</span>
        </a>
        <form method="GET" action="<?= APP_URL ?>/reportes" class="d-flex gap-2 align-items-center">
            <select name="mes" class="form-select" style="width:auto;">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= $m === $mes ? 'selected' : '' ?>><?= $meses[$m - 1] ?></option>
                <?php endfor; ?>
            </select>
            <select name="anio" class="form-select" style="width:auto;">
                <?php for ($y = date('Y') - 2; $y <= date('Y'); $y++): ?>
                    <option value="<?= $y ?>" <?= $y === $anio ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="btn-primary-custom" title="Filtrar Totales"><i
                    class="fas fa-search"></i></button>
        </form>
    </div>
</div>
</div>

<!-- CAPTURA PARA PDF -->
<!-- CAPTURA PARA PDF -->
<div id="reporte-contenido" class="p-2">
    <!-- El dashboard visual que ve el usuario -->
    <div class="no-pdf">
        <!-- Resumen -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon icon-green"><i class="fas fa-arrow-trend-up"></i></div>
                    <div class="stat-value">$<?= number_format($totalVentas, 2) ?></div>
                    <div class="stat-label">Ingresos (Ventas)</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon icon-red"><i class="fas fa-arrow-trend-down"></i></div>
                    <div class="stat-value">$<?= number_format($totalGastos, 2) ?></div>
                    <div class="stat-label">Egresos (Gastos)</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon <?= $gananciaNeta >= 0 ? 'icon-amber' : 'icon-red' ?>">
                        <i class="fas fa-sack-dollar"></i>
                    </div>
                    <div class="stat-value">
                        <?= $gananciaNeta < 0 ? '-' : '' ?>$<?= number_format(abs($gananciaNeta), 2) ?>
                    </div>
                    <div class="stat-label">Ganancia Neta</div>
                    <div class="stat-sub">
                        <?= $gananciaNeta >= 0 ? '✅ Resultado positivo' : '⚠️ Resultado negativo' ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="table-card">
                    <div
                        class="table-card-header d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2">
                        <div class="flex-grow-1">
                            <h6><i class="fas fa-chart-bar me-2" style="color:var(--accent)"></i>Ventas del Periodo</h6>
                            <span class="text-muted small"
                                style="font-size:10px;"><?= date('d/m/Y', strtotime($chart_inicio)) ?> -
                                <?= date('d/m/Y', strtotime($chart_fin)) ?></span>
                        </div>
                        <form method="GET" action="<?= APP_URL ?>/reportes" class="d-flex gap-1">
                            <input type="hidden" name="mes" value="<?= $mes ?>">
                            <input type="hidden" name="anio" value="<?= $anio ?>">
                            <input type="date" name="chart_inicio" class="form-control form-control-sm"
                                value="<?= $chart_inicio ?>" style="font-size:10px; padding:2px 5px; width:110px;">
                            <input type="date" name="chart_fin" class="form-control form-control-sm"
                                value="<?= $chart_fin ?>" style="font-size:10px; padding:2px 5px; width:110px;">
                            <button type="submit" class="btn-sm-icon"
                                style="padding:2px 8px; border-color:var(--accent);"><i class="fas fa-sync"
                                    style="font-size:10px;"></i></button>
                            <a href="<?= APP_URL ?>/reportes?mes=<?= $mes ?>&anio=<?= $anio ?>"
                                class="btn-sm-icon d-flex align-items-center justify-content-center"
                                style="padding:2px 8px; border:1px solid var(--border); color:var(--text-muted); background:var(--bg-card2);"
                                title="Restablecer Rango">
                                <i class="fas fa-undo" style="font-size:10px;"></i>
                            </a>
                        </form>
                    </div>
                    <div class="p-3"><canvas id="ventasDiaChart" height="220"></canvas></div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="table-card">
                    <div class="table-card-header">
                        <h6><i class="fas fa-chart-pie me-2" style="color:var(--accent-red)"></i>Gastos por Categoría
                        </h6>
                        <span class="text-muted small" style="font-size:9px;">(Usa el filtro de la izquierda)</span>
                    </div>
                    <div class="p-3"><canvas id="gastosCatChart" height="220"></canvas></div>
                </div>
            </div>
            <div class="col-12">
                <div class="table-card">
                    <div class="table-card-header">
                        <h6><i class="fas fa-scale-balanced me-2" style="color:var(--accent-blue)"></i>Comparativo
                            Ingresos vs Egresos</h6>
                    </div>
                    <div class="p-3"><canvas id="comparativoChart" height="120"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <!-- VISTA PROFESIONAL EXCLUSIVA PARA PDF (Oculta en web) -->
    <div id="pdf-view"
        style="display:none; background: #fff; color: #000; font-family: 'Times New Roman', Times, serif; padding: 40px; border: 1px solid #eee;">
        <div style="text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px;">
            <h1 style="margin: 0; font-size: 28px; color: #1a1f2e;">CATNIS BAKERY</h1>
            <p style="margin: 5px 0; color: #666; font-size: 14px;">Reporte Financiero Detallado</p>
            <h2 style="margin: 15px 0 0; font-size: 18px; text-transform: uppercase; letter-spacing: 2px;">Periodo:
                <?= date('d/m/Y', strtotime($mesIni)) ?> AL <?= date('d/m/Y', strtotime($mesFin)) ?></h2>
        </div>

        <div style="margin-bottom: 40px;">
            <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; font-size: 16px; color: #333;">1. RESUMEN
                EJECUTIVO</h3>
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <tr>
                    <td style="padding: 10px; border: 1px solid #eee; font-weight: bold; width: 70%;">Total Ingresos
                        Mensuales</td>
                    <td
                        style="padding: 10px; border: 1px solid #eee; text-align: right; color: green; font-weight: bold;">
                        $<?= number_format($totalVentas, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #eee; font-weight: bold;">Total Egresos Mensuales
                    </td>
                    <td
                        style="padding: 10px; border: 1px solid #eee; text-align: right; color: red; font-weight: bold;">
                        $<?= number_format($totalGastos, 0, ',', '.') ?></td>
                </tr>
                <tr style="background: #f9f9f9;">
                    <td style="padding: 10px; border: 1px solid #333; font-weight: bold; font-size: 16px;">UTILIDAD
                        / PÉRDIDA NETA</td>
                    <td
                        style="padding: 10px; border: 1px solid #333; text-align: right; font-weight: bold; font-size: 16px; color: <?= $gananciaNeta >= 0 ? 'green' : 'red' ?>;">
                        $<?= number_format($gananciaNeta, 0, ',', '.') ?>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-bottom: 40px;">
            <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; font-size: 16px; color: #333;">2. DETALLE
                DE OPERACIONES</h3>
            <div style="display: flex; gap: 40px; margin-top: 15px;">
                <div style="flex: 1;">
                    <h4 style="font-size: 14px; margin-bottom: 10px;">Ventas por Día</h4>
                    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                        <thead>
                            <tr style="background: #eee;">
                                <th style="padding: 5px; border: 1px solid #ccc; text-align: left;">Día</th>
                                <th style="padding: 5px; border: 1px solid #ccc; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ventasPorDia as $v): ?>
                                <tr>
                                    <td style="padding: 5px; border: 1px solid #eee;">
                                        <?= date('d/m', strtotime($v['fecha_dia'])) ?></td>
                                    <td style="padding: 5px; border: 1px solid #eee; text-align: right;">
                                        $<?= number_format($v['total'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div style="flex: 1;">
                    <h4 style="font-size: 14px; margin-bottom: 10px;">Gastos por Categoría</h4>
                    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                        <thead>
                            <tr style="background: #eee;">
                                <th style="padding: 5px; border: 1px solid #ccc; text-align: left;">Categoría</th>
                                <th style="padding: 5px; border: 1px solid #ccc; text-align: right;">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gastosPorCategoria as $g): ?>
                                <tr>
                                    <td style="padding: 5px; border: 1px solid #eee;"><?= ucfirst($g['categoria']) ?>
                                    </td>
                                    <td style="padding: 5px; border: 1px solid #eee; text-align: right;">
                                        $<?= number_format($g['total'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div
            style="margin-top: 60px; text-align: right; color: #999; font-size: 10px; border-top: 1px solid #eee; padding-top: 10px;">
            Generado por Catnis Bakery Management System | <?= date('d/m/Y H:i:s') ?>
        </div>
    </div>
</div>
<!-- Fin reporte-contenido -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<?php
$labelsVentas = [];
$dataVentas = [];
foreach ($ventasAgrupadas as $v) {
    $labelsVentas[] = date('d M', strtotime($v['fecha_dia']));
    $dataVentas[] = (float) $v['total'];
}

$catLabels = json_encode(array_map(fn($g) => ucfirst($g['categoria']), $gastosPorCategoria));
$catTotales = json_encode(array_column($gastosPorCategoria, 'total'));
$labelPeriodo = date('d_M_Y', strtotime($chart_inicio)) . '_al_' . date('d_M_Y', strtotime($chart_fin));

$extraJs = '<script>
function exportarPDF() {
    const pdfView = document.getElementById("pdf-view");
    const noPdf = document.querySelector(".no-pdf");
    
    pdfView.style.display = "block";
    noPdf.style.display = "none";

    const opt = {
        margin:       [0.5, 0.5],
        filename:     "Reporte_Financiero_CatnisBakery_' . $labelPeriodo . '.pdf",
        image:        { type: "jpeg", quality: 0.98 },
        html2canvas:  { scale: 2, backgroundColor: "#ffffff" },
        jsPDF:        { unit: "in", format: "letter", orientation: "portrait" }
    };
    
    html2pdf().set(opt).from(pdfView).save().then(() => {
        pdfView.style.display = "none";
        noPdf.style.display = "block";
    });
}

new Chart(document.getElementById("ventasDiaChart"), {
    type: "line",
    data: {
        labels: ' . json_encode($labelsVentas) . ',
        datasets: [{
            label: "Ventas",
            data: ' . json_encode($dataVentas) . ',
            borderColor: "rgba(245,158,11,1)",
            backgroundColor: "rgba(245,158,11,0.1)",
            fill: true, tension: 0.4, pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: "rgba(255,255,255,0.05)" }, ticks: { color: "#94a3b8" } },
            y: { grid: { color: "rgba(255,255,255,0.05)" }, ticks: { color: "#94a3b8", callback: v => "$" + v.toLocaleString() } }
        }
    }
});

new Chart(document.getElementById("gastosCatChart"), {
    type: "doughnut",
    data: {
        labels: ' . $catLabels . ',
        datasets: [{
            data: ' . $catTotales . ',
            backgroundColor: ["#f59e0b","#ef4444","#3b82f6","#10b981","#8b5cf6","#f97316"],
            borderWidth: 2, borderColor: "#1a1f2e",
        }]
    },
    options: { responsive: true, plugins: { legend: { labels: { color: "#94a3b8", font: { size: 12 } } } } }
});

new Chart(document.getElementById("comparativoChart"), {
    type: "bar",
    data: {
        labels: ["Periodo Seleccionado"],
        datasets: [
            { label: "Ingresos", data: [' . $chart_total_ventas . '], backgroundColor: "rgba(16,185,129,0.7)", borderRadius: 6 },
            { label: "Gastos",   data: [' . $chart_total_gastos . '], backgroundColor: "rgba(239,68,68,0.7)",   borderRadius: 6 },
            { label: "Ganancia", data: [' . $chart_ganancia_neta . '], backgroundColor: "rgba(245,158,11,0.7)", borderRadius: 6 },
        ]
    },
    options: {
        responsive: true, indexAxis: "y",
        plugins: { legend: { labels: { color: "#94a3b8" } } },
        scales: {
            x: { grid: { color: "rgba(255,255,255,0.05)" }, ticks: { color: "#94a3b8", callback: v => "$" + v.toLocaleString() } },
            y: { grid: { display: false }, ticks: { color: "#94a3b8" } }
        }
    }
});
</script>';

echo $extraJs;
require_once APP_ROOT . '/views/layout/footer.php';
?>