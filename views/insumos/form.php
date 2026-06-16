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
                        <input type="number" name="stock" class="form-control" min="0" step="1"
                               value="<?= (int)($insumo['stock'] ?? 0) ?>"
                               <?= $editando ? '' : 'placeholder="0"' ?>>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stock mínimo</label>
                        <input type="number" name="stock_minimo" class="form-control" min="0" step="1"
                               value="<?= (int)($insumo['stock_minimo'] ?? 0) ?>"
                               placeholder="Alerta si baja de...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Costo unitario ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" name="costo_unitario" id="costoUnitario" class="form-control"
                                   value="<?= $editando ? number_format((float)($insumo['costo_unitario'] ?? 0), 2, ',', '.') : '' ?>"
                                   placeholder="0,00" oninput="formatCosto(this)">
                        </div>
                        <div style="font-size:11px;color:var(--text-dim);margin-top:4px;">
                            Precio por <strong id="labelUnidad"><?= htmlspecialchars($insumo['unidad_medida'] ?? 'unidad') ?></strong>
                        </div>

                        <!-- Calculadora de costo por unidad -->
                        <div style="margin-top:10px;">
                            <button type="button" onclick="toggleCalc()"
                                style="background:none;border:1px dashed rgba(251,191,36,0.4);border-radius:8px;padding:6px 14px;font-size:12px;color:var(--accent);cursor:pointer;width:100%;text-align:left;">
                                <i class="fas fa-calculator me-2"></i>
                                Calcular desde precio de compra (bulto / paquete)
                                <i class="fas fa-chevron-down ms-1" id="calcChevron" style="float:right;margin-top:2px;"></i>
                            </button>

                            <div id="calculadora" style="display:none;background:rgba(251,191,36,0.05);border:1px solid rgba(251,191,36,0.2);border-radius:0 0 10px 10px;padding:14px 16px;">
                                <div class="row g-2 align-items-end">
                                    <div class="col-5">
                                        <label style="font-size:12px;color:var(--text-muted);">Precio del bulto / paquete</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">$</span>
                                            <input type="text" id="precioBulto" class="form-control"
                                                   placeholder="130.000" oninput="formatCalc(this); recalcular()">
                                        </div>
                                    </div>
                                    <div class="col-1 text-center pb-1" style="font-size:18px;color:var(--text-dim);">÷</div>
                                    <div class="col-4">
                                        <label style="font-size:12px;color:var(--text-muted);">Cantidad total</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" id="cantidadBulto" class="form-control text-center"
                                                   placeholder="25000" min="0.001" step="any" oninput="recalcular()">
                                            <span class="input-group-text" id="labelUnidadCalc" style="font-size:11px;">
                                                <?= htmlspecialchars($insumo['unidad_medida'] ?? 'unid') ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-2 text-end">
                                        <button type="button" id="btnUsarCosto" onclick="usarCostoCalculado()"
                                            class="btn-primary-custom w-100" style="padding:6px 0;font-size:12px;display:none;">
                                            <i class="fas fa-check me-1"></i>Usar
                                        </button>
                                    </div>
                                </div>

                                <!-- Resultado -->
                                <div id="resultadoCalc" style="display:none;margin-top:10px;padding:10px 12px;background:rgba(16,185,129,0.1);border-radius:8px;border:1px solid rgba(16,185,129,0.25);">
                                    <div style="font-size:12px;color:var(--text-muted);">Costo por unidad calculado:</div>
                                    <div style="font-size:18px;font-weight:700;color:#10b981;">
                                        $<span id="resultadoValor">0</span>
                                        <span style="font-size:13px;font-weight:400;"> por <span id="resultadoUnidad"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
// Sincronizar etiqueta de unidad con el select
document.querySelector('[name="unidad_medida"]').addEventListener('change', function() {
    document.getElementById('labelUnidad').textContent = this.value;
    document.getElementById('labelUnidadCalc').textContent = this.value;
});

function toggleCalc() {
    const calc = document.getElementById('calculadora');
    const chevron = document.getElementById('calcChevron');
    const visible = calc.style.display !== 'none';
    calc.style.display = visible ? 'none' : 'block';
    chevron.className = visible ? 'fas fa-chevron-down ms-1' : 'fas fa-chevron-up ms-1';
}

function formatCalc(input) {
    let val = input.value.replace(/\D/g, '');
    input.value = val ? new Intl.NumberFormat('es-CO').format(parseInt(val)) : '';
}

function parseCOP(val) {
    return parseFloat((val || '0').replace(/\./g, '').replace(',', '.')) || 0;
}

let costoCalculado = 0;

function recalcular() {
    const precio   = parseCOP(document.getElementById('precioBulto').value);
    const cantidad = parseFloat(document.getElementById('cantidadBulto').value) || 0;
    const unidad   = document.querySelector('[name="unidad_medida"]').value;
    const res      = document.getElementById('resultadoCalc');
    const btn      = document.getElementById('btnUsarCosto');

    if (precio > 0 && cantidad > 0) {
        costoCalculado = precio / cantidad;
        document.getElementById('resultadoValor').textContent =
            costoCalculado.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 4 });
        document.getElementById('resultadoUnidad').textContent = unidad;
        res.style.display = 'block';
        btn.style.display = 'block';
    } else {
        res.style.display = 'none';
        btn.style.display = 'none';
    }
}

function usarCostoCalculado() {
    if (!costoCalculado) return;
    const formatted = costoCalculado.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 4 });
    document.getElementById('costoUnitario').value = formatted;

    // Animación de confirmación
    const btn = document.getElementById('btnUsarCosto');
    btn.innerHTML = '<i class="fas fa-check me-1"></i>¡Aplicado!';
    btn.style.background = 'rgba(16,185,129,0.8)';
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Usar';
        btn.style.background = '';
    }, 1500);
}

function formatCosto(input) {
    // Permite decimales (ej: 5,20)
    const raw = input.value;
    const hasComma = raw.includes(',');
    const parts = raw.split(',');
    const intPart = parts[0].replace(/\D/g, '');
    const decPart = hasComma ? (parts[1] || '').replace(/\D/g, '').substring(0, 4) : null;
    let formatted = intPart ? new Intl.NumberFormat('es-CO', { maximumFractionDigits: 0 }).format(parseInt(intPart) || 0) : '';
    if (decPart !== null) formatted += ',' + decPart;
    input.value = formatted;
}

document.querySelector('form').addEventListener('submit', function() {
    const c = document.getElementById('costoUnitario');
    c.value = parseCOP(c.value);
});
</script>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
