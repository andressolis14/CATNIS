<?php
$pageTitle = 'Inventario de Productos';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h4><i class="fas fa-boxes-stacked me-2" style="color:var(--accent)"></i>Inventario</h4>
        <p>Gestión de productos y stock</p>
    </div>
    <a href="<?= APP_URL ?>/productos/crear" class="btn-primary-custom">
        <i class="fas fa-plus me-2"></i>Nuevo Producto
    </a>
</div>

<div class="table-card">
    <?php if (empty($productos)): ?>
        <div class="p-5 text-center">
            <i class="fas fa-box-open fa-3x mb-3" style="color:var(--text-dim)"></i>
            <h6 style="color:var(--text-muted)">Sin productos registrados</h6>
            <a href="<?= APP_URL ?>/productos/crear" class="btn-primary-custom mt-3 d-inline-block">Agregar primero</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>P. Compra</th>
                        <th>P. Venta</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $p): ?>
                        <tr>
                        <td class="text-dim"><?= $p['id'] ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <?php if ($p['imagen']): ?>
                                        <img src="<?= APP_URL ?>/img/productos/<?= $p['imagen'] ?>" alt="" style="width:40px; height:40px; object-fit:cover; border-radius:8px; border:1px solid var(--border);">
                                    <?php else: ?>
                                        <div style="width:40px; height:40px; background:var(--bg-card2); border-radius:8px; display:flex; align-items:center; justify-content:center; color:var(--text-dim);">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="fw-600 text-main"><?= htmlspecialchars($p['nombre']) ?></div>
                                    <?php if ($p['descripcion']): ?>
                                        <div class="text-dim" style="font-size:11px;"><?= htmlspecialchars(mb_substr($p['descripcion'], 0, 40)) ?>...</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">$<?= number_format($p['precio_compra'], 0, ',', '.') ?></td>
                        <td class="text-green fw-bold">$<?= number_format($p['precio_venta'], 0, ',', '.') ?></td>
                        <td>
                            <span class="fw-bold <?= $p['stock'] <= $p['stock_minimo'] ? 'text-red' : 'text-green' ?>">
                                <?= $p['stock'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($p['stock'] <= 0): ?>
                                <span class="badge-pendiente">Sin Stock</span>
                            <?php elseif ($p['stock'] <= $p['stock_minimo']): ?>
                                <span class="badge-credito">Bajo Stock</span>
                            <?php else: ?>
                                <span class="badge-pagada">OK</span>
                            <?php endif; ?>
                        </td>
                            <td class="text-end">
                                <a href="<?= APP_URL ?>/productos/editar?id=<?= $p['id'] ?>" class="btn-sm-icon me-1" style="background:rgba(59,130,246,0.15);color:var(--accent-blue);" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="<?= APP_URL ?>/productos/eliminar?id=<?= $p['id'] ?>" class="btn-sm-icon" style="background:rgba(239,68,68,0.15);color:var(--accent-red);" title="Eliminar"
                                   onclick="return confirm('¿Eliminar este producto?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
