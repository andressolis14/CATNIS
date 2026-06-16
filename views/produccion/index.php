<?php
$pageTitle = 'Historial de Producción';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold"><i class="fas fa-industry me-2" style="color:var(--accent)"></i>Producción</h4>
        <p style="font-size:13px;color:var(--text-muted);">Registro de lotes producidos y descuento automático de insumos.</p>
    </div>
    <a href="<?= APP_URL ?>/produccion/crear" class="btn-primary-custom">
        <i class="fas fa-plus me-2"></i>Nueva Producción
    </a>
</div>

<div class="table-card">
    <?php if (empty($producciones)): ?>
        <div class="p-5 text-center">
            <i class="fas fa-industry fa-3x mb-3" style="color:var(--text-dim)"></i>
            <h6 style="color:var(--text-muted)">Sin producciones registradas</h6>
            <p style="font-size:13px;color:var(--text-dim);">Registra un lote de producción para descontar insumos automáticamente.</p>
            <a href="<?= APP_URL ?>/produccion/crear" class="btn-primary-custom mt-2 d-inline-block">
                Registrar primera producción
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th class="text-center">Unidades producidas</th>
                        <th>Notas</th>
                        <th>Registrado por</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($producciones as $p): ?>
                    <tr>
                        <td class="text-dim" style="font-size:12px;">#<?= $p['id'] ?></td>
                        <td style="color:var(--text-muted);font-size:13px;">
                            <?= date('d/m/Y', strtotime($p['fecha'])) ?>
                        </td>
                        <td>
                            <div class="fw-600"><?= htmlspecialchars($p['producto_nombre']) ?></div>
                            <?php if ($p['receta_nombre']): ?>
                                <div style="font-size:11px;color:var(--text-dim);"><?= htmlspecialchars($p['receta_nombre']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span style="font-size:15px;font-weight:700;color:var(--accent);">
                                <?= round((float)$p['cantidad_producida'], 2) + 0 ?>
                            </span>
                            <span style="font-size:11px;color:var(--text-muted);"> unid.</span>
                        </td>
                        <td style="font-size:13px;color:var(--text-muted);">
                            <?= $p['notas'] ? htmlspecialchars($p['notas']) : '—' ?>
                        </td>
                        <td style="font-size:12px;color:var(--text-dim);"><?= htmlspecialchars($p['usuario_nombre']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
