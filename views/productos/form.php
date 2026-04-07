<?php
$editando   = isset($producto);
$pageTitle  = $editando ? 'Editar Producto' : 'Nuevo Producto';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header">
    <h4><i class="fas fa-<?= $editando ? 'pen' : 'plus-circle' ?> me-2" style="color:var(--accent)"></i><?= $pageTitle ?></h4>
    <p><a href="<?= APP_URL ?>/productos" style="color:var(--accent);text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Volver al inventario</a></p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="form-card">
            <form method="POST" action="<?= $editando ? APP_URL . '/productos/editar?id=' . $producto['id'] : APP_URL . '/productos/crear' ?>" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Imagen del Producto (Opcional)</label>
                        <?php if ($editando && $producto['imagen']): ?>
                            <div class="mb-2">
                                <img src="<?= APP_URL ?>/img/productos/<?= $producto['imagen'] ?>" alt="Actual" style="width:80px; height:80px; object-fit:cover; border-radius:10px; border:1px solid var(--border);">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="imagen" class="form-control" accept="image/*">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Nombre del Producto *</label>
                        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre'] ?? '') ?>" placeholder="Ej: Pastel de Pollo" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2" placeholder="Descripción opcional..."><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Precio de Compra *</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:var(--bg-card2);border:1px solid var(--border);border-right:none;color:var(--text-muted);">$</span>
                            <input type="number" step="1" min="0" name="precio_compra" class="form-control" style="border-left:none;" value="<?= (int)($producto['precio_compra'] ?? 0) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Precio de Venta *</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:var(--bg-card2);border:1px solid var(--border);border-right:none;color:var(--text-muted);">$</span>
                            <input type="number" step="1" min="0" name="precio_venta" class="form-control" style="border-left:none;" value="<?= (int)($producto['precio_venta'] ?? 0) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stock Actual *</label>
                        <input type="number" min="0" name="stock" class="form-control" value="<?= $producto['stock'] ?? '0' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stock Mínimo (alerta)</label>
                        <input type="number" min="0" name="stock_minimo" class="form-control" value="<?= $producto['stock_minimo'] ?? '5' ?>">
                    </div>
                    <div class="col-12 mt-2">
                        <button type="submit" class="btn-primary-custom me-2">
                            <i class="fas fa-<?= $editando ? 'floppy-disk' : 'plus' ?> me-2"></i>
                            <?= $editando ? 'Actualizar Producto' : 'Crear Producto' ?>
                        </button>
                        <a href="<?= APP_URL ?>/productos" style="color:var(--text-muted);text-decoration:none;font-size:14px;">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
