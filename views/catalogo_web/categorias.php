<?php require_once APP_ROOT . '/views/layout/header.php'; ?>

<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold"><i class="fas fa-tags me-2" style="color:var(--accent)"></i>Categorías del Catálogo</h4>
        <p class="text-muted" style="font-size:14px;">Estas categorías aparecen como filtros en la página web.</p>
    </div>
    <a href="<?= APP_URL ?>/catalogoweb" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Volver a Productos
    </a>
</div>

<div class="row g-4">
    <!-- Lista de categorías -->
    <div class="col-lg-7">
        <div class="table-card">
            <?php if (empty($categorias)): ?>
                <div class="p-5 text-center text-muted">No hay categorías aún.</div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Icono</th>
                            <th>Nombre</th>
                            <th class="text-center">Orden</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td style="font-size:1.4rem;"><?= $cat['icono'] ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($cat['nombre']) ?></td>
                            <td class="text-center text-muted"><?= $cat['orden'] ?></td>
                            <td class="text-center">
                                <?php if ($cat['activo']): ?>
                                    <span class="badge bg-success bg-opacity-15 text-success px-2 py-1">Activa</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-15 text-danger px-2 py-1">Oculta</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1"
                                        onclick="cargarEditar(<?= $cat['id'] ?>, '<?= htmlspecialchars(addslashes($cat['nombre'])) ?>', '<?= htmlspecialchars($cat['icono']) ?>', <?= $cat['orden'] ?>, <?= $cat['activo'] ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="<?= APP_URL ?>/catalogoweb/eliminar_categoria?id=<?= $cat['id'] ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('¿Eliminar esta categoría? Los productos quedarán sin categoría.')">
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
    </div>

    <!-- Formulario nueva / editar categoría -->
    <div class="col-lg-5">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2" id="formTitle">Nueva Categoría</h6>
            <form action="<?= APP_URL ?>/catalogoweb/guardar_categoria" method="POST">
                <input type="hidden" name="id" id="cat_id" value="0">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="cat_nombre" class="form-control"
                           placeholder="Ej: Pasteles" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Icono (emoji)</label>
                        <input type="text" name="icono" id="cat_icono" class="form-control"
                               placeholder="🎂" value="🐾" maxlength="5">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Orden</label>
                        <input type="number" name="orden" id="cat_orden" class="form-control" min="0" value="0">
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="activo" id="cat_activo" checked>
                        <label class="form-check-label fw-bold" for="cat_activo">Categoría activa</label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-custom px-4">
                        <i class="fas fa-save me-1"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">Limpiar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cargarEditar(id, nombre, icono, orden, activo) {
    document.getElementById('cat_id').value = id;
    document.getElementById('cat_nombre').value = nombre;
    document.getElementById('cat_icono').value = icono;
    document.getElementById('cat_orden').value = orden;
    document.getElementById('cat_activo').checked = activo == 1;
    document.getElementById('formTitle').textContent = 'Editar Categoría';
    document.getElementById('cat_nombre').focus();
}

function resetForm() {
    document.getElementById('cat_id').value = 0;
    document.getElementById('cat_nombre').value = '';
    document.getElementById('cat_icono').value = '🐾';
    document.getElementById('cat_orden').value = 0;
    document.getElementById('cat_activo').checked = true;
    document.getElementById('formTitle').textContent = 'Nueva Categoría';
}
</script>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
