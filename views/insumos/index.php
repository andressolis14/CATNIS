<?php
$pageTitle = 'Insumos';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold"><i class="fas fa-flask me-2" style="color:var(--accent)"></i>Insumos</h4>
        <p class="text-muted" style="font-size:14px;">Materias primas y materiales de producción.</p>
    </div>
    <a href="<?= APP_URL ?>/insumos/crear" class="btn-primary-custom">
        <i class="fas fa-plus me-2"></i>Nuevo Insumo
    </a>
</div>

<?php if (!empty($bajoStock)): ?>
<div class="alert alert-warning d-flex align-items-center gap-2 mb-4" style="border-radius:10px;">
    <i class="fas fa-triangle-exclamation fa-lg"></i>
    <div>
        <strong><?= count($bajoStock) ?> insumo(s) con stock bajo:</strong>
        <?= implode(', ', array_map(fn($i) => htmlspecialchars($i['nombre']), $bajoStock)) ?>
    </div>
</div>
<?php endif; ?>

<div class="table-card">
    <?php if (empty($insumos)): ?>
        <div class="p-5 text-center">
            <i class="fas fa-flask fa-3x mb-3" style="color:var(--text-dim)"></i>
            <h6 style="color:var(--text-muted)">Sin insumos registrados</h6>
            <a href="<?= APP_URL ?>/insumos/crear" class="btn-primary-custom mt-3 d-inline-block">Agregar primer insumo</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th class="text-center">Unidad</th>
                        <th class="text-center">Stock Actual</th>
                        <th class="text-center">Stock Mínimo</th>
                        <th class="text-end">Costo Unit.</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($insumos as $ins): ?>
                        <?php $bajo = $ins['stock'] <= $ins['stock_minimo']; ?>
                        <tr>
                            <td class="fw-600">
                                <?= htmlspecialchars($ins['nombre']) ?>
                                <?php if ($ins['descripcion']): ?>
                                    <div style="font-size:11px;color:var(--text-dim);"><?= htmlspecialchars($ins['descripcion']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span style="background:rgba(59,130,246,0.1);color:#3b82f6;font-size:11px;padding:3px 8px;border-radius:20px;font-weight:600;">
                                    <?= htmlspecialchars($ins['unidad_medida']) ?>
                                </span>
                            </td>
                            <td class="text-center fw-bold <?= $bajo ? 'text-danger' : 'text-success' ?>">
                                <?= number_format($ins['stock'], 3) ?>
                            </td>
                            <td class="text-center" style="color:var(--text-muted);">
                                <?= number_format($ins['stock_minimo'], 3) ?>
                            </td>
                            <td class="text-end" style="color:var(--accent);">
                                $<?= number_format($ins['costo_unitario'], 0, ',', '.') ?>
                            </td>
                            <td class="text-center">
                                <?php if ($ins['stock'] == 0): ?>
                                    <span class="badge bg-danger bg-opacity-15 text-danger p-2" style="font-size:11px;">Sin stock</span>
                                <?php elseif ($bajo): ?>
                                    <span class="badge bg-warning bg-opacity-15 text-warning p-2" style="font-size:11px;">Stock bajo</span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-15 text-success p-2" style="font-size:11px;">OK</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= APP_URL ?>/insumos/editar?id=<?= $ins['id'] ?>" class="btn-sm-icon me-1"
                                   style="background:rgba(59,130,246,0.1);color:#3b82f6;" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button type="button" class="btn-sm-icon"
                                    style="background:rgba(239,68,68,0.1);color:#ef4444;" title="Eliminar"
                                    onclick="if(confirm('¿Eliminar este insumo?')) window.location.href='<?= APP_URL ?>/insumos/eliminar?id=<?= $ins['id'] ?>'">
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
