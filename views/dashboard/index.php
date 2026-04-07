<?php
$pageTitle = 'Dashboard';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header">
    <h4><i class="fas fa-chart-pie me-2" style="color:var(--accent)"></i>Dashboard</h4>
    <p>Resumen financiero de hoy y el mes en curso</p>
</div>

<!-- KPIs -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon icon-green"><i class="fas fa-arrow-trend-up"></i></div>
            <div class="stat-value text-green">$<?= number_format($totalVentasMes, 0, ',', '.') ?></div>
            <div class="stat-label">Ventas del Mes</div>
            <div class="stat-sub"><i class="fas fa-calendar-day me-1"></i>Hoy: $<?= number_format($ventasHoy, 0, ',', '.') ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon icon-red"><i class="fas fa-arrow-trend-down"></i></div>
            <div class="stat-value text-red">$<?= number_format($totalGastosMes, 0, ',', '.') ?></div>
            <div class="stat-label">Gastos del Mes</div>
            <div class="stat-sub"><i class="fas fa-calendar-day me-1"></i>Hoy: $<?= number_format($gastosHoy, 0, ',', '.') ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon <?= $gananciaNeta >= 0 ? 'icon-amber' : 'icon-red' ?>">
                <i class="fas fa-sack-dollar"></i>
            </div>
            <div class="stat-value <?= $gananciaNeta >= 0 ? 'text-amber' : 'text-red' ?>">
                $<?= number_format(abs($gananciaNeta), 0, ',', '.') ?>
            </div>
            <div class="stat-label">Ganancia Neta</div>
            <div class="stat-sub"><?= $gananciaNeta >= 0 ? '✅ Positiva' : '⚠️ Pérdida' ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon icon-blue"><i class="fas fa-file-invoice-dollar"></i></div>
            <div class="stat-value text-blue">$<?= number_format($totalDeudas, 0, ',', '.') ?></div>
            <div class="stat-label">Por Cobrar</div>
            <div class="stat-sub"><?= count($deudas) ?> deuda(s) pendiente(s)</div>
        </div>
    </div>
</div>

<!-- Balance por Canal -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-card" style="border-left: 4px solid #10b981;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label" style="font-size:11px; text-transform: uppercase; letter-spacing:1px;">💵 Fondos en Efectivo</div>
                    <div class="stat-value text-green" style="font-size:22px;">$<?= number_format($totalEfectivo, 0, ',', '.') ?></div>
                </div>
                <div class="stat-icon icon-green" style="width:40px; height:40px; font-size:16px;"><i class="fas fa-money-bill-1"></i></div>
            </div>
            <div class="stat-sub" style="font-size:10px; color:var(--text-dim)">Ingresos directos + Abonos de deudas</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card" style="border-left: 4px solid #3b82f6;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label" style="font-size:11px; text-transform: uppercase; letter-spacing:1px;">🏦 Saldo en Banco / Transf.</div>
                    <div class="stat-value text-blue" style="font-size:22px;">$<?= number_format($totalBanco, 0, ',', '.') ?></div>
                </div>
                <div class="stat-icon icon-blue" style="width:40px; height:40px; font-size:16px;"><i class="fas fa-university"></i></div>
            </div>
            <div class="stat-sub" style="font-size:10px; color:var(--text-dim)">Ventas por transferencia + Abonos bancarios</div>
        </div>
    </div>
</div>

<!-- Gráfico + Bajo stock -->
<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="table-card">
            <div class="table-card-header">
                <h6><i class="fas fa-chart-bar me-2" style="color:var(--accent)"></i>Ventas por Día (Mes Actual)</h6>
            </div>
            <div class="p-3">
                <canvas id="ventasChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="table-card h-100">
            <div class="table-card-header">
                <h6><i class="fas fa-triangle-exclamation me-2" style="color:var(--accent-red)"></i>Bajo Stock</h6>
                <a href="<?= APP_URL ?>/productos" class="btn btn-sm" style="background:var(--bg-card2);color:var(--accent);border:1px solid var(--border);font-size:12px;border-radius:6px;">Ver todo</a>
            </div>
            <?php if (empty($bajoStock)): ?>
                <div class="p-4 text-center" style="color:var(--text-dim);font-size:13px;">
                    <i class="fas fa-check-circle fa-2x mb-2" style="color:var(--accent-green)"></i><br>¡Inventario OK!
                </div>
            <?php else: ?>
                <div class="p-3">
                    <?php foreach ($bajoStock as $p): ?>
                        <div class="d-flex align-items-center justify-content-between py-2" style="border-bottom:1px solid var(--border);">
                            <div>
                                <div style="font-size:13px;font-weight:500;"><?= htmlspecialchars($p['nombre']) ?></div>
                                <div style="font-size:11px;color:var(--text-dim);">Mínimo: <?= $p['stock_minimo'] ?></div>
                            </div>
                            <span class="badge-pendiente"><?= $p['stock'] ?> uds</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Más vendidos + Deudas recientes -->
<div class="row g-3">
    <div class="col-lg-6">
        <div class="table-card">
            <div class="table-card-header">
                <h6><i class="fas fa-star me-2" style="color:var(--accent)"></i>Más Vendidos</h6>
            </div>
            <?php if (empty($masVendidos)): ?>
                <div class="p-4 text-center" style="color:var(--text-dim);font-size:13px;">Sin datos de ventas aún.</div>
            <?php else: ?>
                <table class="table">
                    <thead><tr><th>Producto</th><th class="text-end">Vendidos</th></tr></thead>
                    <tbody>
                    <?php foreach ($masVendidos as $i => $prod): ?>
                        <tr>
                            <td><span style="color:var(--accent);margin-right:8px;">#<?= $i+1 ?></span><?= htmlspecialchars($prod['nombre']) ?></td>
                            <td class="text-end fw-bold"><?= $prod['total_vendido'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="table-card">
            <div class="table-card-header">
                <h6><i class="fas fa-file-invoice-dollar me-2" style="color:var(--accent-blue)"></i>Deudas Pendientes</h6>
                <a href="<?= APP_URL ?>/deudas" style="color:var(--accent);font-size:12px;text-decoration:none;">Ver todas</a>
            </div>
            <?php if (empty($deudas)): ?>
                <div class="p-4 text-center" style="color:var(--text-dim);font-size:13px;">
                    <i class="fas fa-check-circle fa-2x mb-2" style="color:var(--accent-green)"></i><br>Sin deudas pendientes
                </div>
            <?php else: ?>
                <table class="table">
                    <thead><tr><th>Cliente</th><th class="text-end">Saldo</th><th>Estado</th></tr></thead>
                    <tbody>
                    <?php foreach (array_slice($deudas, 0, 5) as $d): ?>
                        <tr>
                            <td><?= htmlspecialchars($d['cliente']) ?></td>
                            <td class="text-end text-red fw-bold">$<?= number_format($d['saldo'], 0, ',', '.') ?></td>
                            <td><span class="badge-<?= $d['estado'] ?>"><?= ucfirst($d['estado']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Preparar datos para gráfico
$dias = array_column($ventasMes, 'dia');
$totales = array_column($ventasMes, 'total');
$extraJs = '
<script>
const ctx = document.getElementById("ventasChart").getContext("2d");
new Chart(ctx, {
    type: "bar",
    data: {
        labels: ' . json_encode($dias) . ',
        datasets: [{
            label: "Ventas ($)",
            data: ' . json_encode($totales) . ',
            backgroundColor: "rgba(245,158,11,0.6)",
            borderColor: "rgba(245,158,11,1)",
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: "rgba(255,255,255,0.05)" }, ticks: { color: "#94a3b8" } },
            y: { grid: { color: "rgba(255,255,255,0.05)" }, ticks: { color: "#94a3b8", callback: v => "$" + v } }
        }
    }
});
</script>';
require_once APP_ROOT . '/views/layout/footer.php';
?>
