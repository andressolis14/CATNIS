<?php require_once APP_ROOT . '/views/layout/header.php'; ?>

<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold"><i class="fas fa-store me-2" style="color:var(--accent)"></i>Catálogo Web</h4>
        <p class="text-muted" style="font-size:14px;">Productos que aparecen en la página pública.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= APP_URL ?>/catalogoweb/categorias" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-tags me-1"></i> Categorías
        </a>
        <a href="<?= APP_URL ?>/catalogoweb/crear" class="btn btn-primary-custom btn-sm">
            <i class="fas fa-plus me-1"></i> Nuevo Producto
        </a>
    </div>
</div>

<?php if (empty($productos)): ?>
    <div class="table-card p-5 text-center">
        <i class="fas fa-store" style="font-size:3rem; color:var(--accent); opacity:.3;"></i>
        <p class="mt-3 text-muted">No hay productos en el catálogo aún.</p>
        <a href="<?= APP_URL ?>/catalogoweb/crear" class="btn btn-primary-custom btn-sm mt-2">
            <i class="fas fa-plus me-1"></i> Agregar el primero
        </a>
    </div>
<?php else: ?>
    <!-- Filtro por categoría -->
    <?php if (!empty($categorias)): ?>
    <div class="d-flex gap-2 flex-wrap mb-3">
        <a href="<?= APP_URL ?>/catalogoweb" class="btn btn-sm <?= !isset($_GET['cat']) ? 'btn-primary-custom' : 'btn-outline-secondary' ?>">
            Todos (<?= count($productos) ?>)
        </a>
        <?php foreach ($categorias as $cat): ?>
            <?php $cnt = count(array_filter($productos, fn($p) => $p['categoria_id'] == $cat['id'])); ?>
            <a href="<?= APP_URL ?>/catalogoweb?cat=<?= $cat['id'] ?>"
               class="btn btn-sm <?= ($_GET['cat'] ?? '') == $cat['id'] ? 'btn-primary-custom' : 'btn-outline-secondary' ?>">
                <?= $cat['icono'] ?> <?= htmlspecialchars($cat['nombre']) ?> (<?= $cnt ?>)
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:70px">Imagen</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th class="text-end">Precio</th>
                        <th class="text-center">Orden</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $filtro = $_GET['cat'] ?? '';
                    foreach ($productos as $p):
                        if ($filtro && $p['categoria_id'] != $filtro) continue;
                    ?>
                    <tr>
                        <td>
                            <?php if ($p['imagen']): ?>
                                <img src="<?= APP_URL ?>/img/catalogo/<?= htmlspecialchars($p['imagen']) ?>"
                                     style="width:52px;height:52px;object-fit:cover;border-radius:10px;">
                            <?php else: ?>
                                <div style="width:52px;height:52px;border-radius:10px;background:var(--sidebar-hover);display:flex;align-items:center;justify-content:center;font-size:1.4rem;">🐾</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($p['nombre']) ?></div>
                            <?php if ($p['descripcion']): ?>
                                <small class="text-muted"><?= htmlspecialchars(mb_strimwidth($p['descripcion'], 0, 60, '...')) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($p['categoria_nombre']): ?>
                                <span class="badge bg-secondary bg-opacity-15 text-secondary px-2 py-1">
                                    <?= $p['categoria_icono'] ?> <?= htmlspecialchars($p['categoria_nombre']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted small">Sin categoría</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end fw-bold">$<?= number_format($p['precio'], 0, ',', '.') ?></td>
                        <td class="text-center text-muted"><?= $p['orden'] ?></td>
                        <td class="text-center">
                            <?php if ($p['activo']): ?>
                                <span class="badge bg-success bg-opacity-15 text-success px-2 py-1">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-15 text-danger px-2 py-1">Oculto</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="<?= APP_URL ?>/catalogoweb/editar?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= APP_URL ?>/catalogoweb/eliminar?id=<?= $p['id'] ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('¿Eliminar este producto del catálogo?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
