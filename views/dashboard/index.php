<?php
$pageTitle = 'Dashboard';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div
    class="page-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
    <div>
        <h4><i class="fas fa-chart-pie me-2" style="color:var(--accent)"></i>Dashboard</h4>
        <p>Resumen financiero inteligente</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-success shadow-sm" style="border-radius:10px; padding:8px 16px;"
            data-bs-toggle="modal" data-bs-target="#modalCapital">
            <i class="fas fa-sack-dollar me-2"></i>Inyectar Capital
        </button>
        <a href="<?= APP_URL ?>/dashboard" class="btn btn-sm"
            style="background:var(--bg-card2); border:1px solid var(--border); color:var(--text-muted); border-radius:10px; padding:8px 16px;">
            <i class="fas fa-undo me-2"></i>Resetear Todo
        </a>
    </div>
</div>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><i
            class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['exito'])): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['exito']) ?>
    </div>
    <?php unset($_SESSION['exito']); ?>
<?php endif; ?>

<!-- KPIs -->
<div class="row g-3 mb-4">
    <!-- VENTAS -->
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon icon-green mb-3"><i class="fas fa-arrow-trend-up"></i></div>
            <div class="stat-value">$<?= number_format($totalVentasTerm, 0, ',', '.') ?></div>
            <div class="stat-label">Ventas del Periodo</div>
            <div class="stat-sub"><i class="fas fa-calendar-day me-1"></i>Hoy:
                $<?= number_format($ventasHoy, 0, ',', '.') ?></div>

            <form class="stat-card-filters mt-3" method="GET">
                <input type="date" name="v_ini" value="<?= $v_ini ?>" title="Inicio">
                <input type="date" name="v_fin" value="<?= $v_fin ?>" title="Fin">
                <button type="submit"><i class="fas fa-filter"></i></button>
                <a href="<?= APP_URL ?>/dashboard" class="reset-link"><i class="fas fa-times"></i></a>
            </form>
        </div>
    </div>
    <!-- GASTOS -->
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon icon-red mb-3"><i class="fas fa-arrow-trend-down"></i></div>
            <div class="stat-value">$<?= number_format($totalGastosTerm, 0, ',', '.') ?></div>
            <div class="stat-label">Gastos del Periodo</div>
            <div class="stat-sub"><i class="fas fa-calendar-day me-1"></i>Hoy:
                $<?= number_format($gastosHoy, 0, ',', '.') ?></div>

            <form class="stat-card-filters mt-3" method="GET">
                <input type="date" name="g_ini" value="<?= $g_ini ?>">
                <input type="date" name="g_fin" value="<?= $g_fin ?>">
                <button type="submit"><i class="fas fa-filter"></i></button>
                <a href="<?= APP_URL ?>/dashboard" class="reset-link"><i class="fas fa-times"></i></a>
            </form>
        </div>
    </div>
    <!-- GANANCIA -->
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon <?= $gananciaNetaTerm >= 0 ? 'icon-amber' : 'icon-red' ?> mb-3">
                <i class="fas fa-sack-dollar"></i>
            </div>
            <div class="stat-value">
                $<?= number_format(abs($gananciaNetaTerm), 0, ',', '.') ?>
            </div>
            <div class="stat-label">Ganancia Neta</div>
            <div class="stat-sub"><?= $gananciaNetaTerm >= 0 ? '✅ Positiva' : '⚠️ Pérdida' ?></div>

            <form class="stat-card-filters mt-3" method="GET">
                <input type="date" name="gn_ini" value="<?= $gn_ini ?>">
                <input type="date" name="gn_fin" value="<?= $gn_fin ?>">
                <button type="submit"><i class="fas fa-filter"></i></button>
                <a href="<?= APP_URL ?>/dashboard" class="reset-link"><i class="fas fa-times"></i></a>
            </form>
        </div>
    </div>
    <!-- POR COBRAR -->
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon icon-blue mb-3"><i class="fas fa-file-invoice-dollar"></i></div>
            <div class="stat-value">$<?= number_format($totalDeudasTerm, 0, ',', '.') ?></div>
            <div class="stat-label">Por Cobrar</div>
            <div class="stat-sub"><?= count($deudasTerm) ?> deuda(s) en rango</div>

            <form class="stat-card-filters mt-3" method="GET">
                <input type="date" name="pc_ini" value="<?= $pc_ini ?>">
                <input type="date" name="pc_fin" value="<?= $pc_fin ?>">
                <button type="submit"><i class="fas fa-filter"></i></button>
                <a href="<?= APP_URL ?>/dashboard" class="reset-link"><i class="fas fa-times"></i></a>
            </form>
        </div>
    </div>
</div>

<!-- Balance por Canal -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-card" style="border-left: 4px solid #10b981;">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label"
                        style="font-size:11px; text-transform: uppercase; letter-spacing:1px; margin:0;">💵 Fondos en
                        Efectivo</div>
                    <div class="stat-value" style="font-size:22px;">
                        $<?= number_format($totalEfectivoTerm, 0, ',', '.') ?></div>
                    <div class="stat-sub" style="font-size:10px; color:var(--text-dim)">Ingresos directos + Abonos de
                        deudas</div>
                </div>
                <div class="stat-icon icon-green" style="width:40px; height:40px; font-size:16px; margin:0;"><i
                        class="fas fa-money-bill-1"></i></div>
            </div>

            <div style="font-size:11px;color:var(--text-dim);margin-top:8px;">Acumulado total</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card" style="border-left: 4px solid #3b82f6;">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label"
                        style="font-size:11px; text-transform: uppercase; letter-spacing:1px; margin:0;">🏦 Saldo en
                        Banco / Transf.</div>
                    <div class="stat-value" style="font-size:22px;">$<?= number_format($totalBancoTerm, 0, ',', '.') ?>
                    </div>
                    <div class="stat-sub" style="font-size:10px; color:var(--text-dim)">Ventas por transferencia +
                        Abonos bancarios</div>
                </div>
                <div class="stat-icon icon-blue" style="width:40px; height:40px; font-size:16px; margin:0;"><i
                        class="fas fa-university"></i></div>
            </div>
            <div style="font-size:11px;color:var(--text-dim);margin-top:8px;">Acumulado total</div>
        </div>
    </div>
</div>

<!-- Gráfico + Bajo stock -->
<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="table-card h-100">
            <div class="table-card-header d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">
                <div class="flex-grow-1">
                    <h6><i class="fas fa-chart-bar me-2" style="color:var(--accent)"></i>Movimiento de Ventas</h6>
                    <span class="text-muted small"
                        style="font-size:10px;"><?= date('d/m/Y', strtotime($chart_inicio)) ?> -
                        <?= date('d/m/Y', strtotime($chart_fin)) ?></span>
                </div>
                <form method="GET" action="<?= APP_URL ?>/dashboard" class="d-flex gap-2">
                    <input type="date" name="chart_inicio" class="form-control form-control-sm"
                        value="<?= $chart_inicio ?>" style="font-size:11px; padding:4px 8px; width:120px;">
                    <input type="date" name="chart_fin" class="form-control form-control-sm" value="<?= $chart_fin ?>"
                        style="font-size:11px; padding:4px 8px; width:120px;">
                    <button type="submit" class="btn-sm-icon"
                        style="padding:4px 10px; border-color:var(--accent); color:var(--accent); background:rgba(245,158,11,0.1);"><i
                            class="fas fa-filter"></i></button>
                    <a href="<?= APP_URL ?>/dashboard"
                        class="btn-sm-icon d-flex align-items-center justify-content-center"
                        style="padding:4px 10px; border:1px solid var(--border); color:var(--text-muted); background:var(--bg-card2);"
                        title="Restablecer">
                        <i class="fas fa-undo"></i>
                    </a>
                </form>
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
                <a href="<?= APP_URL ?>/productos" class="btn btn-sm"
                    style="background:var(--bg-card2);color:var(--accent);border:1px solid var(--border);font-size:12px;border-radius:6px;">Ver
                    todo</a>
            </div>
            <?php if (empty($bajoStock)): ?>
                <div class="p-4 text-center" style="color:var(--text-dim);font-size:13px;">
                    <i class="fas fa-check-circle fa-2x mb-2" style="color:var(--accent-green)"></i><br>¡Inventario OK!
                </div>
            <?php else: ?>
                <div class="p-3">
                    <?php foreach ($bajoStock as $p): ?>
                        <div class="d-flex align-items-center justify-content-between py-2"
                            style="border-bottom:1px solid var(--border);">
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
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-end">Vendidos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($masVendidos as $i => $prod): ?>
                            <tr>
                                <td><span
                                        style="color:var(--accent);margin-right:8px;">#<?= $i + 1 ?></span><?= htmlspecialchars($prod['nombre']) ?>
                                </td>
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
                <h6><i class="fas fa-file-invoice-dollar me-2" style="color:var(--accent-blue)"></i>Deudas Pendientes
                </h6>
                <a href="<?= APP_URL ?>/deudas" style="color:var(--accent);font-size:12px;text-decoration:none;">Ver
                    todas</a>
            </div>
            <?php if (empty($deudas)): ?>
                <div class="p-4 text-center" style="color:var(--text-dim);font-size:13px;">
                    <i class="fas fa-check-circle fa-2x mb-2" style="color:var(--accent-green)"></i><br>Sin deudas
                    pendientes
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th class="text-end">Saldo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($deudasTerm, 0, 5) as $d): ?>
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

<!-- Modal Inyectar Capital -->
<div class="modal fade" id="modalCapital" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-success text-white py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-sack-dollar me-2"></i>Inyectar Capital Inicial</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= APP_URL ?>/caja/inyectarCapital" method="POST">
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Registra el saldo que ya tenías disponible antes de empezar a usar
                        el sistema en esta fecha.</p>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Fecha de Registro</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i
                                    class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Efectivo ($)</label>
                            <input type="number" name="monto_efectivo" class="form-control" placeholder="0" min="0"
                                step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Transferencia ($)</label>
                            <input type="number" name="monto_banco" class="form-control" placeholder="0" min="0"
                                step="0.01">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success px-4 py-2 fw-bold" style="border-radius: 10px;">
                        GUARDAR CAPITAL
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Preparar datos para gráfico
$labels = [];
$totales = [];
foreach ($ventasAgrupadas as $v) {
    $labels[] = date('d M', strtotime($v['fecha_dia']));
    $totales[] = (float) $v['total'];
}

$extraJs = '
<script>
const ctx = document.getElementById("ventasChart").getContext("2d");
new Chart(ctx, {
    type: "bar",
    data: {
        labels: ' . json_encode($labels) . ',
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
            y: { grid: { color: "rgba(255,255,255,0.05)" }, ticks: { color: "#94a3b8", callback: v => "$" + v.toLocaleString() } }
        }
    }
});
</script>';
require_once APP_ROOT . '/views/layout/footer.php';
?>