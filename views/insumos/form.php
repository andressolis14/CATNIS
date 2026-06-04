<?php
$editando  = isset($insumo);
$pageTitle = $editando ? 'Editar Insumo' : 'Nuevo Insumo';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header mb-4">
    <h4 class="fw-bold"><i class="fas fa-flask me-2" style="color:var(--accent)"></i><?= $pageTitle ?></h4>
    <p><a href="<?= APP_URL ?>/insumos" style="color:var(--accent);text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Volver a Insumos</a></p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="form-card">
            <form method="POST" action="<?= $editando ? APP_URL.'/insumos/editar?id='.$insumo['id'] : APP_URL.'/insumos/crear' ?>">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nombre <span style="color:var(--accent)">*</span></label>
                        <input type="text" name="nombre" class="form-control" required
                               value="<?= htmlspecialchars($insumo['nombre'] ?? '') ?>"
                               placeholder="Ej: Harina de trigo, Azúcar, Huevos...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción (opcional)</label>
                        <input type="text" name="descripcion" class="form-control"
                               value="<?= htmlspecialchars($insumo['descripcion'] ?? '') ?>"
                               placeholder="Detalles adicionales...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Unidad de medida <span style="color:var(--accent)">*</span></label>
                        <select name="unidad_medida" class="form-select">
                            <?php
                            $unidades = ['kg','g','lb','lt','ml','unid','paquete','docena','caja','botella'];
                            $selec = $insumo['unidad_medida'] ?? 'unid';
                            foreach ($unidades as $u):
                            ?>
                                <option value="<?= $u ?>" <?= $selec === $u ? 'selected' : '' ?>><?= $u ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stock actual</label>
                        <input type="number" name="stock" class="form-control" min="0" step="0.001"
                               value="<?= $insumo['stock'] ?? 0 ?>"
                               <?= $editando ? '' : 'placeholder="0"' ?>>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stock mínimo</label>
                        <input type="number" name="stock_minimo" class="form-control" min="0" step="0.001"
                               value="<?= $insumo['stock_minimo'] ?? 0 ?>"
                               placeholder="Alerta si baja de...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Costo unitario ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" name="costo_unitario" class="form-control"
                                   value="<?= $editando ? number_format($insumo['costo_unitario'], 0, ',', '.') : '' ?>"
                                   placeholder="0" oninput="formatCosto(this)">
                        </div>
                        <div style="font-size:11px;color:var(--text-dim);margin-top:4px;">Precio por <?= htmlspecialchars($insumo['unidad_medida'] ?? 'unidad') ?></div>
                    </div>
                    <div class="col-12 mt-2 text-center">
                        <button type="submit" class="btn-primary-custom px-5">
                            <i class="fas fa-<?= $editando ? 'floppy-disk' : 'check' ?> me-2"></i>
                            <?= $editando ? 'Guardar Cambios' : 'Crear Insumo' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function formatCosto(input) {
    let val = input.value.replace(/\D/g, '');
    input.value = val ? new Intl.NumberFormat('es-CO').format(parseInt(val)) : '';
}
document.querySelector('form').addEventListener('submit', function() {
    const c = document.querySelector('[name="costo_unitario"]');
    c.value = c.value.replace(/\./g, '').replace(',', '.');
});
</script>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
