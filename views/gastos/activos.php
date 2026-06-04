<?php
$pageTitle = 'Activos';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold"><i class="fas fa-box-archive me-2" style="color:#10b981"></i>Activos Adquiridos</h4>
        <p class="text-muted" style="font-size:14px;">Equipos, máquinas y bienes registrados como activos del negocio.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= APP_URL ?>/gastos" class="btn" style="background:var(--bg-card2);border:1px solid var(--border);color:var(--text-muted);border-radius:10px;padding:8px 16px;text-decoration:none;">
            <i class="fas fa-arrow-left me-1"></i> Volver a Gastos
        </a>
        <a href="<?= APP_URL ?>/gastos/crear" class="btn-primary-custom">
            <i class="fas fa-plus me-2"></i>Registrar Activo
        </a>
    </div>
</div>

<!-- Resumen -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div style="font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Total Activos</div>
            <div style="font-size:28px;font-weight:700;color:#10b981;margin-top:6px;"><?= count($activos) ?></div>
            <div style="font-size:12px;color:var(--text-dim);margin-top:4px;">Bienes registrados</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div style="font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Inversión Total</div>
            <div style="font-size:28px;font-weight:700;color:var(--accent);margin-top:6px;">$<?= number_format($totalActivos, 0, ',', '.') ?></div>
            <div style="font-size:12px;color:var(--text-dim);margin-top:4px;">Valor de adquisición</div>
        </div>
    </div>
</div>

<?php if (empty($activos)): ?>
    <div class="table-card p-5 text-center">
        <i class="fas fa-box-archive fa-3x mb-3" style="color:var(--text-dim)"></i>
        <h6 style="color:var(--text-muted)">Sin activos registrados</h6>
        <p style="font-size:13px;color:var(--text-dim)">Registra un gasto con categoría <strong>Activos</strong> para que aparezca aquí.</p>
        <a href="<?= APP_URL ?>/gastos/crear" class="btn-primary-custom mt-2 d-inline-block">
            <i class="fas fa-plus me-2"></i>Registrar primer activo
        </a>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($activos as $a): ?>
            <?php
                $nombre = !empty($a['descripcion']) ? $a['descripcion'] : (!empty($a['detalles']) ? $a['detalles'][0]['descripcion'] : 'Activo sin nombre');
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="stat-card h-100" style="border-left:3px solid #10b981;">
                    <!-- Cabecera -->
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div style="font-size:15px;font-weight:700;color:var(--text-main);"><?= htmlspecialchars($nombre) ?></div>
                            <div style="font-size:12px;color:var(--text-dim);margin-top:2px;">
                                <i class="fas fa-calendar-alt me-1"></i><?= date('d/m/Y', strtotime($a['fecha'])) ?>
                                <?php if ($a['numero_factura']): ?>
                                    &nbsp;·&nbsp;<span style="color:var(--accent);"><?= htmlspecialchars($a['numero_factura']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div style="font-size:18px;font-weight:700;color:#10b981;white-space:nowrap;">
                            $<?= number_format($a['monto'], 0, ',', '.') ?>
                        </div>
                    </div>

                    <!-- Ítems del activo -->
                    <?php if (!empty($a['detalles'])): ?>
                        <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:8px;padding:8px;margin-top:10px;">
                            <?php foreach ($a['detalles'] as $det): ?>
                                <div class="d-flex justify-content-between align-items-center py-1" style="border-bottom:1px solid rgba(255,255,255,0.04);font-size:12px;">
                                    <span style="color:var(--text-muted);">
                                        <?= htmlspecialchars($det['descripcion']) ?>
                                        <?php if (!empty($det['unidad_medida']) && $det['unidad_medida'] !== 'unid'): ?>
                                            <span style="color:var(--text-dim);"> · <?= htmlspecialchars($det['unidad_medida']) ?></span>
                                        <?php endif; ?>
                                    </span>
                                    <span style="color:var(--text-main);font-weight:600;">
                                        <?= $det['cantidad'] ?> × $<?= number_format($det['monto'], 0, ',', '.') ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Acciones -->
                    <div class="d-flex gap-2 mt-3">
                        <a href="<?= APP_URL ?>/gastos/detalle?id=<?= $a['id'] ?>" class="btn btn-sm flex-fill" style="background:rgba(245,158,11,0.1);color:var(--accent);border:1px solid rgba(245,158,11,0.2);border-radius:8px;font-size:12px;">
                            <i class="fas fa-file-invoice me-1"></i>Ver detalle
                        </a>
                        <a href="<?= APP_URL ?>/gastos/editar?id=<?= $a['id'] ?>" class="btn btn-sm" style="background:rgba(59,130,246,0.1);color:#3b82f6;border:1px solid rgba(59,130,246,0.2);border-radius:8px;font-size:12px;">
                            <i class="fas fa-pen"></i>
                        </a>
                        <button type="button" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border:1px solid rgba(239,68,68,0.2);border-radius:8px;font-size:12px;"
                            onclick="if(confirm('¿Eliminar este activo?')) window.location.href='<?= APP_URL ?>/gastos/eliminar?id=<?= $a['id'] ?>'">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
