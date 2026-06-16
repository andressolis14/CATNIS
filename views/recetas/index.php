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
                            Rinde <?= round((float)$r['rendimiento'], 2) + 0 ?> unid.
                        </span>
                    </div>

                    <!-- Insumos de la receta -->
                    <?php
                        $ins = (new Receta())->insumosPorReceta($r['id']);
                        // Calcular costos
                        $costoInsumos  = 0;
                        foreach ($ins as $i) $costoInsumos += $i['cantidad'] * (float)$i['costo_unitario'];
                        $costoEnergia  = (float)($r['costo_energia'] ?? 0);
                        $costoTotal    = $costoInsumos + $costoEnergia;
                        $rendimiento   = max((float)$r['rendimiento'], 0.001);
                        $costoPorUnid  = $costoTotal / $rendimiento;
                        $rentabilidad  = isset($r['rentabilidad']) && $r['rentabilidad'] !== null
                                         ? (float)$r['rentabilidad'] : null;
                    ?>
                    <?php if (!empty($ins)): ?>
                        <!-- Lista de insumos -->
                        <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:8px;padding:8px;margin-bottom:8px;">
                            <?php foreach ($ins as $i): ?>
                                <?php
                                    $qty = (float)$i['cantidad'];
                                    $qtyFmt = ($qty == floor($qty))
                                        ? number_format((int)$qty, 0, ',', '.')
                                        : rtrim(rtrim(number_format($qty, 3, ',', '.'), '0'), ',');
                                ?>
                                <div class="d-flex justify-content-between align-items-center py-1" style="font-size:12px;border-bottom:1px solid rgba(255,255,255,0.04);">
                                    <span style="color:var(--text-muted);"><?= htmlspecialchars($i['insumo_nombre']) ?></span>
                                    <span style="color:var(--text-main);font-weight:600;">
                                        <?= $qtyFmt ?> <?= htmlspecialchars($i['unidad_medida']) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Análisis de costos -->
                        <?php if ($costoTotal > 0): ?>
                        <div style="background:rgba(251,191,36,0.05);border:1px solid rgba(251,191,36,0.18);border-radius:8px;padding:10px;margin-bottom:10px;">
                            <div style="font-size:10px;font-weight:700;color:var(--text-dim);letter-spacing:.5px;margin-bottom:6px;">
                                <i class="fas fa-calculator me-1"></i>ANÁLISIS DE COSTOS
                            </div>

                            <?php foreach ($ins as $i): ?>
                                <?php $sub = $i['cantidad'] * (float)$i['costo_unitario']; if ($sub <= 0) continue; ?>
                                <div class="d-flex justify-content-between" style="font-size:11px;color:var(--text-muted);margin-bottom:3px;">
                                    <span><?= htmlspecialchars($i['insumo_nombre']) ?>
                                        <span style="opacity:.6;">(<?= $qtyFmt ?> × $<?= number_format((float)$i['costo_unitario'], 2, ',', '.') ?>)</span>
                                    </span>
                                    <span>$<?= number_format($sub, 0, ',', '.') ?></span>
                                </div>
                            <?php endforeach; ?>

                            <?php if ($costoEnergia > 0): ?>
                                <div class="d-flex justify-content-between" style="font-size:11px;color:var(--text-muted);margin-bottom:3px;">
                                    <span><i class="fas fa-bolt me-1" style="color:var(--accent);font-size:9px;"></i>Energía / Otros</span>
                                    <span>$<?= number_format($costoEnergia, 0, ',', '.') ?></span>
                                </div>
                            <?php endif; ?>

                            <div style="border-top:1px solid rgba(251,191,36,0.25);margin-top:7px;padding-top:7px;">
                                <div class="d-flex justify-content-between mb-1">
                                    <span style="font-size:12px;font-weight:700;">Costo total receta</span>
                                    <span style="font-size:12px;font-weight:700;color:var(--accent);">$<?= number_format($costoTotal, 0, ',', '.') ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span style="font-size:11px;color:var(--text-muted);">Costo / unidad producida</span>
                                    <span style="font-size:12px;font-weight:600;color:#10b981;">$<?= number_format($costoPorUnid, 0, ',', '.') ?></span>
                                </div>
                                <?php if ($rentabilidad !== null && $costoPorUnid > 0): ?>
                                <?php
                                    $colorMargen   = $rentabilidad >= 30 ? '#10b981' : ($rentabilidad >= 0 ? '#f59e0b' : '#ef4444');
                                    $precioSugerido = ($rentabilidad < 100 && $rentabilidad > 0)
                                        ? $costoPorUnid / (1 - $rentabilidad / 100)
                                        : null;
                                ?>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span style="font-size:11px;color:var(--text-muted);">
                                        <i class="fas fa-percent me-1" style="font-size:9px;"></i>Rentabilidad
                                        <span style="font-weight:700;color:<?= $colorMargen ?>;margin-left:4px;">
                                            <?= number_format($rentabilidad, 1, ',', '.') ?>%
                                        </span>
                                    </span>
                                    <?php if ($precioSugerido): ?>
                                    <span style="font-size:12px;font-weight:700;color:#3b82f6;">
                                        Vender a $<?= number_format($precioSugerido, 0, ',', '.') ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

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
