<?php
$editando  = isset($receta);
$pageTitle = $editando ? 'Editar Receta' : 'Nueva Receta';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header mb-4">
    <h4 class="fw-bold"><i class="fas fa-book-open me-2" style="color:var(--accent)"></i><?= $pageTitle ?></h4>
    <p><a href="<?= APP_URL ?>/recetas" style="color:var(--accent);text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Volver a Recetas</a></p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-card">
            <form method="POST" action="<?= $editando ? APP_URL.'/recetas/editar?id='.$receta['id'] : APP_URL.'/recetas/crear' ?>">
                <div class="row g-3">
                    <!-- Producto -->
                    <div class="col-md-6">
                        <label class="form-label">Producto <span style="color:var(--accent)">*</span></label>
                        <?php if ($editando): ?>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($receta['producto_nombre']) ?>" readonly style="background:rgba(255,255,255,0.03);">
                            <input type="hidden" name="producto_id" value="<?= $receta['producto_id'] ?>">
                        <?php else: ?>
                            <select name="producto_id" class="form-select" required>
                                <option value="" disabled selected>— Selecciona un producto —</option>
                                <?php foreach ($productos as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <!-- Rendimiento -->
                    <div class="col-md-3">
                        <label class="form-label">Rendimiento</label>
                        <div class="input-group">
                            <input type="number" name="rendimiento" class="form-control" min="0.001" step="0.001"
                                   value="<?= $editando ? $receta['rendimiento'] : 1 ?>">
                            <span class="input-group-text" style="font-size:12px;">unid.</span>
                        </div>
                        <div style="font-size:11px;color:var(--text-dim);margin-top:4px;">¿Cuántas unidades produce esta receta?</div>
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-3">
                        <label class="form-label">Nombre (opcional)</label>
                        <input type="text" name="nombre" class="form-control"
                               value="<?= htmlspecialchars($receta['nombre'] ?? '') ?>"
                               placeholder="Ej: Receta estándar">
                    </div>

                    <!-- Descripción -->
                    <div class="col-12">
                        <label class="form-label">Descripción / Instrucciones (opcional)</label>
                        <textarea name="descripcion" class="form-control" rows="2"
                                  placeholder="Pasos, temperatura, tiempo de horneado..."><?= htmlspecialchars($receta['descripcion'] ?? '') ?></textarea>
                    </div>

                    <hr class="my-2" style="opacity:0.1">

                    <!-- Tabla de insumos -->
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2" style="color:var(--accent)"></i>Insumos necesarios</h6>
                            <button type="button" class="btn-sm-icon" onclick="agregarFila()">
                                <i class="fas fa-plus me-1"></i>Añadir insumo
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table" id="tablaInsumos" style="min-width:500px;">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th style="width:160px;" class="text-center">Cantidad</th>
                                        <th style="width:50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyInsumos">
                                    <?php if ($editando && !empty($receta['insumos'])): ?>
                                        <?php foreach ($receta['insumos'] as $i => $ins): ?>
                                        <tr>
                                            <td>
                                                <select name="insumos[<?= $i ?>][insumo_id]" class="form-select form-select-sm" required>
                                                    <option value="">— Seleccionar —</option>
                                                    <?php foreach ($insumos as $opt): ?>
                                                        <option value="<?= $opt['id'] ?>" <?= $opt['id'] == $ins['insumo_id'] ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($opt['nombre']) ?> (<?= $opt['unidad_medida'] ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="insumos[<?= $i ?>][cantidad]" class="form-control form-control-sm text-center"
                                                       min="0.001" step="any" value="<?= $ins['cantidad'] ?>" required>
                                            </td>
                                            <td><button type="button" class="btn-delete-sm" onclick="this.closest('tr').remove()"><i class="fas fa-trash"></i></button></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr id="filaVacia">
                                            <td colspan="3" class="text-center py-3" style="color:var(--text-dim);font-size:13px;">
                                                <i class="fas fa-flask me-2"></i>Agrega los insumos necesarios
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12 text-center mt-2">
                        <button type="submit" class="btn-primary-custom px-5">
                            <i class="fas fa-<?= $editando ? 'floppy-disk' : 'check' ?> me-2"></i>
                            <?= $editando ? 'Guardar Cambios' : 'Crear Receta' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const insumosOpts = <?= json_encode(array_map(fn($i) => [
    'id'    => $i['id'],
    'label' => $i['nombre'] . ' (' . $i['unidad_medida'] . ')',
], $insumos)) ?>;

let filaIdx = <?= $editando ? count($receta['insumos']) : 0 ?>;

function agregarFila() {
    document.getElementById('filaVacia')?.remove();
    const tbody = document.getElementById('tbodyInsumos');
    const tr = document.createElement('tr');
    const opts = insumosOpts.map(o => `<option value="${o.id}">${o.label}</option>`).join('');
    tr.innerHTML = `
        <td>
            <select name="insumos[${filaIdx}][insumo_id]" class="form-select form-select-sm" required>
                <option value="">— Seleccionar —</option>${opts}
            </select>
        </td>
        <td>
            <input type="number" name="insumos[${filaIdx}][cantidad]" class="form-control form-control-sm text-center"
                   min="0.001" step="any" placeholder="0" required>
        </td>
        <td><button type="button" class="btn-delete-sm" onclick="this.closest('tr').remove()"><i class="fas fa-trash"></i></button></td>
    `;
    tbody.appendChild(tr);
    filaIdx++;
}
</script>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
