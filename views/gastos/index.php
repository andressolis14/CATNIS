<?php
$pageTitle  = 'Gastos';
$categorias = ['servicios', 'compras', 'transporte', 'nomina', 'alquiler', 'prestamos', 'activos', 'otros'];
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h4><i class="fas fa-wallet me-2" style="color:var(--accent)"></i>Gastos</h4>
        <p>Registro y control de salidas de dinero</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= APP_URL ?>/gastos/activos" class="btn" style="background:rgba(16,185,129,0.15); color:#10b981; border:1px solid rgba(16,185,129,0.3); border-radius:10px; padding:8px 16px; font-weight:600; display:flex; align-items:center; gap:8px; text-decoration:none;">
            <i class="fas fa-box-archive"></i> <span>Activos</span>
        </a>
        <a href="<?= APP_URL ?>/gastos/maestro" class="btn" style="background:rgba(139,92,246,0.15); color:var(--accent); border:1px solid rgba(139,92,246,0.2); border-radius:10px; padding:8px 16px; font-weight:600; display:flex; align-items:center; gap:8px; text-decoration:none;">
            <i class="fas fa-boxes"></i> <span>Catálogo de Ítems</span>
        </a>
        <a href="<?= APP_URL ?>/gastos/crear" class="btn-primary-custom">
            <i class="fas fa-plus me-2"></i>Nuevo Gasto
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="form-card mb-4">
    <form method="GET" action="<?= APP_URL ?>/gastos" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Fecha inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?= htmlspecialchars($_GET['fecha_inicio'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Fecha fin</label>
            <input type="date" name="fecha_fin" class="form-control" value="<?= htmlspecialchars($_GET['fecha_fin'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Categoría</label>
            <select name="categoria" class="form-select">
                <option value="">Todas</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat ?>" <?= ($_GET['categoria'] ?? '') === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn-primary-custom flex-grow-1"><i class="fas fa-filter me-2"></i>Filtrar</button>
            <a href="<?= APP_URL ?>/gastos" class="btn" style="background:var(--bg-card2);border:1px solid var(--border);color:var(--text-muted);" title="Limpiar"><i class="fas fa-xmark"></i></a>
        </div>
    </form>
</div>

<div class="table-card">
    <?php if (empty($gastos)): ?>
        <div class="p-5 text-center">
            <i class="fas fa-wallet fa-3x mb-3" style="color:var(--text-dim)"></i>
            <h6 style="color:var(--text-muted)">Sin gastos registrados</h6>
        </div>
    <?php else: ?>
        <?php $totalFiltrado = array_sum(array_column($gastos, 'monto')); ?>
        <div class="px-4 py-2 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--border);">
            <span style="font-size:13px;color:var(--text-muted)"><?= count($gastos) ?> resultado(s)</span>
            <span class="text-red fw-bold">Total: $<?= number_format($totalFiltrado, 0, ',', '.') ?></span>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Fecha</th><th>Factura #</th><th>Resumen / Descripción</th><th>Categoría</th><th>Registrado por</th><th class="text-end">Monto</th><th class="text-end">Acciones</th></tr></thead>
                <tbody>
                <?php foreach ($gastos as $g): ?>
                    <tr>
                        <td class="text-muted" style="font-size:13px"><?= date('d/m/Y', strtotime($g['fecha'])) ?></td>
                        <td class="fw-600 text-main"><?= htmlspecialchars($g['numero_factura'] ?: '—') ?></td>
                        <td>
                            <div class="mb-1" style="font-size:13px; font-weight:500;"><?= htmlspecialchars($g['descripcion'] ?: 'Gasto detallado') ?></div>
                            <?php if (!empty($g['proveedor'])): ?>
                                <div style="font-size:11px;color:var(--text-dim);"><i class="fas fa-store me-1"></i><?= htmlspecialchars($g['proveedor']) ?></div>
                            <?php endif; ?>
                            <?php if (!empty($g['detalles'])): ?>
                                <button class="btn btn-sm p-0 border-0" style="color:var(--accent); font-size:11px; text-decoration:none;" 
                                        onclick="let d = this.nextElementSibling; d.style.display = d.style.display === 'none' ? 'block' : 'none'; this.innerHTML = this.innerHTML.includes('Ver') ? '<i class=\'fas fa-chevron-up me-1\'></i>Ocultar' : '<i class=\'fas fa-chevron-down me-1\'></i>Ver ' + <?= count($g['detalles']) ?> + ' producto(s)';">
                                    <i class="fas fa-chevron-down me-1"></i>Ver <?= count($g['detalles']) ?> producto(s)
                                </button>
                                <div style="display:none; margin-top:8px; padding:8px; background:rgba(255,255,255,0.03); border-radius:6px; border:1px solid var(--border);">
                                    <table style="width:100%; font-size:11px; border-collapse:collapse;">
                                        <thead style="color:var(--text-muted); border-bottom:1px solid var(--border);">
                                            <tr><th style="padding:4px; text-align:left;">Producto</th><th style="padding:4px; text-align:center;">Cant.</th><th style="padding:4px; text-align:right;">Subtotal</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($g['detalles'] as $det): ?>
                                                <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                                                    <td style="padding:4px;"><?= htmlspecialchars($det['descripcion']) ?></td>
                                                    <td style="padding:4px; text-align:center;"><?= $det['cantidad'] ?></td>
                                                    <td style="padding:4px; text-align:right; color:var(--accent-red);">$<?= number_format($det['monto'] * $det['cantidad'], 0, ',', '.') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="background:rgba(139,92,246,0.15);color:var(--accent-purple);font-size:11px;padding:4px 10px;border-radius:20px;font-weight:600;">
                                <?= $g['categoria'] ? ucfirst($g['categoria']) : '—' ?>
                            </span>
                        </td>
                        <td class="text-dim" style="font-size:13px"><?= htmlspecialchars($g['registrado_por']) ?></td>
                        <td class="text-end fw-bold text-red">$<?= number_format($g['monto'], 0, ',', '.') ?></td>
                        <td class="text-end">
                            <a href="<?= APP_URL ?>/gastos/detalle?id=<?= $g['id'] ?>" class="btn-sm-icon me-1" style="border-color:rgba(245,158,11,0.3);background:rgba(245,158,11,0.1);color:var(--accent);" title="Ver Recibo">
                                <i class="fas fa-file-invoice"></i>
                            </a>
                            <a href="<?= APP_URL ?>/gastos/editar?id=<?= $g['id'] ?>" class="btn-sm-icon me-1" style="border-color:rgba(59,130,246,0.3);background:rgba(59,130,246,0.1);color:var(--accent-blue);" title="Editar">
                                <i class="fas fa-pen"></i>
                            </a>
                            <button type="button" class="btn-delete-sm d-inline-flex" style="padding:0;width:32px;height:32px;border-radius:8px;" title="Eliminar"
                               onclick="if(confirm('¿Eliminar este gasto?')) window.location.href='<?= APP_URL ?>/gastos/eliminar?id=<?= $g['id'] ?>'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
