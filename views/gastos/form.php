<?php
$editando   = isset($gasto);
$pageTitle  = $editando ? 'Editar Gasto' : 'Nuevo Gasto';
$categorias = ['servicios', 'compras', 'transporte', 'nomina', 'alquiler', 'otros'];
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header">
    <h4><i class="fas fa-<?= $editando ? 'pen' : 'plus-circle' ?> me-2" style="color:var(--accent)"></i><?= $pageTitle ?></h4>
    <p><a href="<?= APP_URL ?>/gastos" style="color:var(--accent);text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Volver a gastos</a></p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-card">
            <form method="POST" action="<?= $editando ? APP_URL.'/gastos/editar?id='.$gasto['id'] : APP_URL.'/gastos/crear' ?>">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">N° Factura / Recibo</label>
                        <input type="text" name="numero_factura" class="form-control" value="<?= htmlspecialchars($gasto['numero_factura'] ?? '') ?>" placeholder="Ej: FAC-001">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha *</label>
                        <input type="date" name="fecha" class="form-control" value="<?= $gasto['fecha'] ?? date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select">
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat ?>" <?= ($gasto['categoria'] ?? 'otros') === $cat ? 'selected' : '' ?>>
                                    <?= ucfirst($cat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción General (Opcional)</label>
                        <input type="text" name="descripcion_general" class="form-control" value="<?= htmlspecialchars($gasto['descripcion'] ?? '') ?>" placeholder="Resumen del gasto...">
                    </div>

                    <hr class="my-4" style="opacity:0.1">
                    
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><i class="fas fa-list me-2" style="color:var(--accent)"></i>Detalle de la Factura</h6>
                            <button type="button" class="btn-sm-icon" onclick="agregarFila()">
                                <i class="fas fa-plus me-1"></i> Añadir Ítem
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-dark-custom w-100" id="tablaItems" style="min-width: 700px;">
                                <thead>
                                    <tr>
                                        <th>Descripción del Ítem</th>
                                        <th style="width:110px;" class="text-center">Cant.</th>
                                        <th style="width:160px;" class="text-end">Precio Unit. ($)</th>
                                        <th style="width:160px;" class="text-end">Subtotal ($)</th>
                                        <th style="width:60px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyItems">
                                    <?php if ($editando && !empty($gasto['detalles'])): ?>
                                        <?php foreach ($gasto['detalles'] as $i => $det): ?>
                                            <tr>
                                                <td><input type="text" name="items[<?= $i ?>][descripcion]" class="form-control form-control-sm" value="<?= htmlspecialchars($det['descripcion']) ?>" required></td>
                                                <td><input type="number" min="1" name="items[<?= $i ?>][cantidad]" class="form-control form-control-sm cantidad-input text-center" value="<?= $det['cantidad'] ?? 1 ?>" required oninput="calcularTotal()"></td>
                                                <td><input type="text" name="items[<?= $i ?>][monto]" class="form-control form-control-sm monto-input text-end" value="<?= number_format($det['monto'], 0, ',', '.') ?>" required oninput="handleMontoInput(this)"></td>
                                                <td><input type="text" class="form-control form-control-sm subtotal-input text-end" value="<?= number_format(($det['cantidad'] ?? 1) * $det['monto'], 0, ',', '.') ?>" readonly style="background:rgba(255,255,255,0.03);border-color:transparent;color:var(--text-muted);font-weight:600;cursor:default;"></td>
                                                <td><button type="button" class="btn-delete-sm" onclick="eliminarFila(this)"><i class="fas fa-trash"></i></button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td><input type="text" name="items[0][descripcion]" class="form-control form-control-sm" placeholder="Ej: Harina de avena" required></td>
                                            <td><input type="number" min="1" name="items[0][cantidad]" class="form-control form-control-sm cantidad-input text-center" value="1" required oninput="calcularTotal()"></td>
                                            <td><input type="text" name="items[0][monto]" class="form-control form-control-sm monto-input text-end" placeholder="0" required oninput="handleMontoInput(this)"></td>
                                            <td><input type="text" class="form-control form-control-sm subtotal-input text-end" value="$0" readonly style="background:rgba(255,255,255,0.03);border-color:transparent;color:var(--text-muted);font-weight:600;cursor:default;"></td>
                                            <td></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">TOTAL:</th>
                                        <th id="totalGasto" class="text-end" style="color:var(--accent);font-size:18px;">$<?= number_format($gasto['monto'] ?? 0, 0, ',', '.') ?></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="col-12 mt-4 text-center">
                        <button type="submit" class="btn-primary-custom px-5">
                            <i class="fas fa-<?= $editando ? 'floppy-disk' : 'check' ?> me-2"></i>
                            <?= $editando ? 'Guardar Cambios' : 'Finalizar Registro' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let filaIdx = <?= $editando ? count($gasto['detalles']) : 1 ?>;
const DRAFT_KEY = 'catnis_gasto_draft';

// Limpiar puntos antes de enviar y borrar borrador
document.querySelector('form').addEventListener('submit', function() {
    document.querySelectorAll('.monto-input').forEach(input => {
        input.value = input.value.replace(/\./g, '');
    });
    if (!<?= $editando ? 'true' : 'false' ?>) {
        localStorage.removeItem(DRAFT_KEY);
    }
});

function handleMontoInput(input) {
    // Formatear visualmente
    let val = input.value.replace(/\D/g, "");
    if (val !== "") {
        input.value = new Intl.NumberFormat('es-CO').format(val);
    }
    // Calcular totales y guardar borrador
    calcularTotal();
    saveDraft();
}

function saveDraft() {
    if (<?= $editando ? 'true' : 'false' ?>) return;

    const data = {
        numero_factura: document.querySelector('input[name="numero_factura"]').value,
        fecha: document.querySelector('input[name="fecha"]').value,
        categoria: document.querySelector('select[name="categoria"]').value,
        descripcion_general: document.querySelector('input[name="descripcion_general"]').value,
        items: []
    };

    document.querySelectorAll('#tbodyItems tr').forEach(tr => {
        data.items.push({
            descripcion: tr.querySelector('input[name*="[descripcion]"]').value,
            cantidad: tr.querySelector('.cantidad-input').value,
            monto: tr.querySelector('.monto-input').value
        });
    });

    localStorage.setItem(DRAFT_KEY, JSON.stringify(data));
}

function loadDraft() {
    if (<?= $editando ? 'true' : 'false' ?>) return;

    const draft = localStorage.getItem(DRAFT_KEY);
    if (!draft) return;

    try {
        const data = JSON.parse(draft);
        if (!data.items || data.items.length === 0) return;

        document.querySelector('input[name="numero_factura"]').value = data.numero_factura;
        document.querySelector('input[name="fecha"]').value = data.fecha;
        document.querySelector('select[name="categoria"]').value = data.categoria;
        document.querySelector('input[name="descripcion_general"]').value = data.descripcion_general;

        const tbody = document.getElementById('tbodyItems');
        tbody.innerHTML = ''; 
        filaIdx = 0;

        data.items.forEach(item => {
            agregarFila();
            const tr = tbody.lastElementChild;
            tr.querySelector('input[name*="[descripcion]"]').value = item.descripcion;
            tr.querySelector('.cantidad-input').value = item.cantidad;
            tr.querySelector('.monto-input').value = item.monto;
        });
        calcularTotal();
    } catch (e) {
        console.error("Error cargando borrador:", e);
    }
}

function agregarFila() {
    const tbody = document.getElementById('tbodyItems');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" name="items[${filaIdx}][descripcion]" class="form-control form-control-sm" placeholder="Descripción del ítem..." required oninput="saveDraft()"></td>
        <td><input type="number" min="1" name="items[${filaIdx}][cantidad]" class="form-control form-control-sm cantidad-input text-center" value="1" required oninput="saveDraft(); calcularTotal();"></td>
        <td><input type="text" name="items[${filaIdx}][monto]" class="form-control form-control-sm monto-input text-end" placeholder="0" required oninput="handleMontoInput(this)"></td>
        <td><input type="text" class="form-control form-control-sm subtotal-input text-end" value="$0" readonly style="background:rgba(255,255,255,0.03);border-color:transparent;color:var(--text-muted);font-weight:600;cursor:default;"></td>
        <td><button type="button" class="btn-delete-sm" onclick="eliminarFila(this)"><i class="fas fa-trash"></i></button></td>
    `;
    tbody.appendChild(tr);
    filaIdx++;
}

function eliminarFila(btn) {
    btn.closest('tr').remove();
    calcularTotal();
    saveDraft();
}

function calcularTotal() {
    let total = 0;
    document.querySelectorAll('#tbodyItems tr').forEach(tr => {
        const cant = parseInt(tr.querySelector('.cantidad-input').value) || 0;
        const montoRaw = tr.querySelector('.monto-input').value.replace(/\./g, '') || 0;
        const monto = parseInt(montoRaw);
        const sub = (cant * monto);
        total += sub;
        
        // Actualizar input de subtotal visual
        tr.querySelector('.subtotal-input').value = '$' + sub.toLocaleString('es-CO');
    });
    document.getElementById('totalGasto').textContent = '$' + total.toLocaleString('es-CO');
}

// Cargar borrador al iniciar
document.addEventListener('DOMContentLoaded', () => {
    // Escuchar cambios en campos principales
    document.querySelectorAll('input[name="numero_factura"], input[name="fecha"], select[name="categoria"], input[name="descripcion_general"]').forEach(el => {
        el.addEventListener('input', saveDraft);
    });
    
    loadDraft();
});
</script>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
