<?php
$pageTitle = 'Recetas';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold"><i class="fas fa-book-open me-2" style="color:var(--accent)"></i>Recetas</h4>
        <p class="text-muted" style="font-size:14px;">Insumos necesarios para elaborar cada producto.</p>
    </div>
    <a href="<?= APP_URL ?>/recetas/crear" class="btn-primary-custom">
        <i class="fas fa-plus me-2"></i>Nueva Receta
    </a>
</div>

<?php if (empty($recetas)): ?>
    <div class="table-card p-5 text-center">
        <i class="fas fa-book-open fa-3x mb-3" style="color:var(--text-dim)"></i>
        <h6 style="color:var(--text-muted)">Sin recetas registradas</h6>
        <p style="font-size:13px;color:var(--text-dim)">Define qué insumos necesita cada producto para producirlo.</p>
        <a href="<?= APP_URL ?>/recetas/crear" class="btn-primary-custom mt-2 d-inline-block">
            <i class="fas fa-plus me-2"></i>Crear primera receta
        </a>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($recetas as $r): ?>
            <div class="col-md-6 col-lg-4">
                <div class="stat-card h-100" style="border-left:3px solid var(--accent);">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div style="font-size:15px;font-weight:700;color:var(--text-main);">
                                <?= htmlspecialchars($r['producto_nombre']) ?>
                            </div>
                            <?php if ($r['nombre']): ?>
                                <div style="font-size:12px;color:var(--text-muted);margin-top:2px;"><?= htmlspecialchars($r['nombre']) ?></div>
                            <?php endif; ?>
                        </div>
                        <span style="background:rgba(245,158,11,0.1);color:var(--accent);font-size:11px;padding:4px 10px;border-radius:20px;font-weight:600;white-space:nowrap;">
                            Rinde <?= $r['rendimiento'] ?> unid.
                        </span>
                    </div>

                    <!-- Insumos de la receta -->
                    <?php
                        $ins = (new Receta())->insumosPorReceta($r['id']);
                    ?>
                    <?php if (!empty($ins)): ?>
                        <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:8px;padding:8px;margin-bottom:12px;">
                            <?php foreach ($ins as $i): ?>
                                <div class="d-flex justify-content-between align-items-center py-1" style="font-size:12px;border-bottom:1px solid rgba(255,255,255,0.04);">
                                    <span style="color:var(--text-muted);"><?= htmlspecialchars($i['insumo_nombre']) ?></span>
                                    <span style="color:var(--text-main);font-weight:600;">
                                        <?= number_format($i['cantidad'], 3) ?> <?= htmlspecialchars($i['unidad_medida']) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div style="font-size:11px;color:var(--text-dim);"><?= count($ins) ?> insumo(s)</div>
                    <?php endif; ?>

                    <div class="d-flex gap-2 mt-3">
                        <a href="<?= APP_URL ?>/recetas/editar?id=<?= $r['id'] ?>" class="btn btn-sm flex-fill"
                           style="background:rgba(59,130,246,0.1);color:#3b82f6;border:1px solid rgba(59,130,246,0.2);border-radius:8px;font-size:12px;">
                            <i class="fas fa-pen me-1"></i>Editar
                        </a>
                        <button type="button" class="btn btn-sm"
                            style="background:rgba(239,68,68,0.1);color:#ef4444;border:1px solid rgba(239,68,68,0.2);border-radius:8px;font-size:12px;"
                            onclick="if(confirm('¿Eliminar esta receta?')) window.location.href='<?= APP_URL ?>/recetas/eliminar?id=<?= $r['id'] ?>'">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
