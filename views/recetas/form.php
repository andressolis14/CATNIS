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
                            <input type="number" name="rendimiento" class="form-control" min="0" step="any"
                                   value="<?= $editando ? round((float)$receta['rendimiento'], 2) : 1 ?>">
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
                                                       min="0" step="any" value="<?= round((float)$ins['cantidad'], 2) ?>" required>
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

                    <!-- Rentabilidad manual -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-percent me-1" style="color:#10b981"></i>
                            Rentabilidad / Margen (%)
                        </label>
                        <div class="input-group">
                            <input type="number" name="rentabilidad" class="form-control"
                                   min="0" max="100" step="0.1"
                                   value="<?= isset($receta['rentabilidad']) && $receta['rentabilidad'] !== null ? number_format((float)$receta['rentabilidad'], 1, '.', '') : '' ?>"
                                   placeholder="Ej: 56,5">
                            <span class="input-group-text">%</span>
                        </div>
                        <div style="font-size:11px;color:var(--text-dim);margin-top:4px;">Ganancia estimada sobre el costo</div>
                    </div>

                    <!-- Costo de energía -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-bolt me-1" style="color:var(--accent)"></i>
                            Costo de Energía / Otros ($)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" name="costo_energia" id="costoEnergiaInput" class="form-control"
                                   value="<?= $editando ? number_format((float)($receta['costo_energia'] ?? 0), 0, ',', '.') : '' ?>"
                                   placeholder="0" oninput="formatEnergy(this); calcularCostos();">
                        </div>
                        <div style="font-size:11px;color:var(--text-dim);margin-top:4px;">Gas, electricidad, empaques u otros costos fijos por producción</div>
                    </div>

                    <!-- Panel de análisis de costos -->
                    <div class="col-12" id="panelCostos" style="display:none;">
                        <div style="background:rgba(251,191,36,0.06);border:1px solid rgba(251,191,36,0.25);border-radius:12px;padding:16px;">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-calculator me-2" style="color:var(--accent)"></i>
                                Análisis de Costos
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr style="font-size:12px;color:var(--text-muted);">
                                            <th>Insumo</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-end">Costo / u</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyCostos"></tbody>
                                    <tfoot>
                                        <tr id="filaEnergia" style="display:none;">
                                            <td colspan="3" style="font-size:13px;color:var(--text-muted);">
                                                <i class="fas fa-bolt me-1" style="color:var(--accent)"></i>Energía / Otros
                                            </td>
                                            <td class="text-end fw-bold" style="color:var(--accent);" id="costoEnergiaDisplay">$0</td>
                                        </tr>
                                        <tr style="border-top:2px solid rgba(251,191,36,0.4);">
                                            <td colspan="3" class="fw-bold" style="font-size:13px;">COSTO TOTAL RECETA</td>
                                            <td class="text-end fw-bold" style="color:var(--accent);font-size:15px;" id="costoTotalDisplay">$0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="font-size:12px;color:var(--text-muted);">
                                                Costo por unidad producida
                                                <span id="rendimientoLabel" style="color:var(--accent);"></span>
                                            </td>
                                            <td class="text-end fw-bold text-success" id="costoUnidadDisplay">$0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
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
    'id'     => $i['id'],
    'label'  => $i['nombre'] . ' (' . $i['unidad_medida'] . ')',
    'nombre' => $i['nombre'],
    'unidad' => $i['unidad_medida'],
    'costo'  => (float)$i['costo_unitario'],
], $insumos)) ?>;

let filaIdx = <?= $editando ? count($receta['insumos']) : 0 ?>;

function agregarFila() {
    document.getElementById('filaVacia')?.remove();
    const tbody = document.getElementById('tbodyInsumos');
    const tr = document.createElement('tr');
    const opts = insumosOpts.map(o => `<option value="${o.id}">${o.label}</option>`).join('');
    tr.innerHTML = `
        <td>
            <select name="insumos[${filaIdx}][insumo_id]" class="form-select form-select-sm" required onchange="calcularCostos()">
                <option value="">— Seleccionar —</option>${opts}
            </select>
        </td>
        <td>
            <input type="number" name="insumos[${filaIdx}][cantidad]" class="form-control form-control-sm text-center"
                   min="0.001" step="any" placeholder="0" required oninput="calcularCostos()">
        </td>
        <td><button type="button" class="btn-delete-sm" onclick="this.closest('tr').remove(); calcularCostos();"><i class="fas fa-trash"></i></button></td>
    `;
    tbody.appendChild(tr);
    filaIdx++;
}

function formatEnergy(input) {
    let val = input.value.replace(/\D/g, '');
    input.value = val ? new Intl.NumberFormat('es-CO').format(parseInt(val)) : '';
}

function parseCOP(val) {
    return parseFloat((val || '0').toString().replace(/\./g, '').replace(',', '.')) || 0;
}

function calcularCostos() {
    const rows = document.querySelectorAll('#tbodyInsumos tr:not(#filaVacia)');
    const rendimiento = parseFloat(document.querySelector('[name="rendimiento"]').value) || 1;
    const energia = parseCOP(document.getElementById('costoEnergiaInput').value);

    let totalInsumos = 0;
    const tbody = document.getElementById('tbodyCostos');
    tbody.innerHTML = '';

    rows.forEach(tr => {
        const sel = tr.querySelector('select[name*="[insumo_id]"]');
        const cantInput = tr.querySelector('input[name*="[cantidad]"]');
        if (!sel || !cantInput) return;
        const id = parseInt(sel.value);
        const cant = parseFloat(cantInput.value) || 0;
        if (!id || !cant) return;
        const ins = insumosOpts.find(o => o.id === id);
        if (!ins) return;
        const sub = cant * ins.costo;
        totalInsumos += sub;
        tbody.innerHTML += `
            <tr style="font-size:13px;">
                <td>${ins.nombre} <span style="color:var(--text-dim);font-size:11px;">(${ins.unidad})</span></td>
                <td class="text-center">${cant.toLocaleString('es-CO')} ${ins.unidad}</td>
                <td class="text-end">$${ins.costo.toLocaleString('es-CO', {minimumFractionDigits:0,maximumFractionDigits:2})}</td>
                <td class="text-end fw-bold">$${Math.round(sub).toLocaleString('es-CO')}</td>
            </tr>`;
    });

    const total = totalInsumos + energia;
    const costoPorUnidad = rendimiento > 0 ? total / rendimiento : 0;

    document.getElementById('filaEnergia').style.display = energia > 0 ? '' : 'none';
    document.getElementById('costoEnergiaDisplay').textContent = '$' + Math.round(energia).toLocaleString('es-CO');
    document.getElementById('costoTotalDisplay').textContent = '$' + Math.round(total).toLocaleString('es-CO');
    document.getElementById('costoUnidadDisplay').textContent = '$' + Math.round(costoPorUnidad).toLocaleString('es-CO');
    document.getElementById('rendimientoLabel').textContent = rendimiento > 1 ? '(' + rendimiento + ' unid.)' : '';
    document.getElementById('panelCostos').style.display = totalInsumos > 0 ? 'block' : 'none';
}

// Recalcular cuando cambia el rendimiento
document.querySelector('[name="rendimiento"]').addEventListener('input', calcularCostos);

// Recalcular al editar cantidades o selects ya existentes
document.getElementById('tbodyInsumos').addEventListener('change', calcularCostos);
document.getElementById('tbodyInsumos').addEventListener('input', calcularCostos);

// Convertir energía antes de enviar
document.querySelector('form').addEventListener('submit', function() {
    const e = document.getElementById('costoEnergiaInput');
    e.value = parseCOP(e.value);
});

// Calcular al cargar (modo edición)
document.addEventListener('DOMContentLoaded', calcularCostos);
</script>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
