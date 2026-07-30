<?php require_once APP_ROOT . '/views/layout/header.php'; ?>

<div class="page-header mb-4">
    <h4 class="fw-bold">
        <i class="fas fa-<?= $producto ? 'edit' : 'plus' ?> me-2" style="color:var(--accent)"></i>
        <?= $producto ? 'Editar Producto' : 'Nuevo Producto del Catálogo' ?>
    </h4>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="table-card p-4">
            <form action="<?= APP_URL ?>/catalogoweb/<?= $producto ? 'editar?id=' . $producto['id'] : 'crear' ?>"
                  method="POST" enctype="multipart/form-data">

                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre del Producto <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control"
                           value="<?= htmlspecialchars($producto['nombre'] ?? '') ?>" required>
                </div>

                <!-- Categoría + Precio (row) -->
                <div class="row g-3 mb-3">
                    <div class="col-md-7">
                        <label class="form-label fw-bold">Categoría</label>
                        <select name="categoria_id" class="form-select">
                            <option value="">Sin categoría</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id'] ?>"
                                    <?= ($producto['categoria_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                    <?= $cat['icono'] ?> <?= htmlspecialchars($cat['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Precio ($)</label>
                        <input type="number" name="precio" class="form-control" min="0" step="100"
                               value="<?= $producto['precio'] ?? 0 ?>" required>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"
                              placeholder="Descripción corta del producto..."><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
                </div>

                <!-- Imagen -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Imagen</label>
                    <?php if (!empty($producto['imagen'])): ?>
                        <div class="mb-2">
                            <img src="<?= APP_URL ?>/img/catalogo/<?= htmlspecialchars($producto['imagen']) ?>"
                                 style="height:100px;border-radius:12px;object-fit:cover;">
                            <p class="text-muted small mt-1">Sube una nueva imagen para reemplazarla.</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="imagen" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp">
                    <div class="form-text">JPG, PNG o WebP. Recomendado: 600×600px.</div>
                </div>

                <!-- Orden + Activo (row) -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Orden</label>
                        <input type="number" name="orden" class="form-control" min="0"
                               value="<?= $producto['orden'] ?? 0 ?>">
                        <div class="form-text">Menor número = aparece primero.</div>
                    </div>
                    <div class="col-md-8 d-flex align-items-end pb-1">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="activo" id="activo"
                                   <?= ($producto['activo'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="activo">Visible en la página web</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-custom px-4">
                        <i class="fas fa-save me-1"></i> Guardar
                    </button>
                    <a href="<?= APP_URL ?>/catalogoweb" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
