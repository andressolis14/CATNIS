<?php
$pageTitle = 'Registrar Producción';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold"><i class="fas fa-industry me-2" style="color:var(--accent)"></i>Registrar Producción</h4>
        <p><a href="<?= APP_URL ?>/produccion" style="color:var(--accent);text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Volver</a></p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="form-card">
            <form method="POST" action="<?= APP_URL ?>/produccion/crear" id="formProduccion">
                <div class="row g-3">

                    <div class="col-md-7">
                        <label class="form-label">Receta / Producto <span style="color:var(--accent)">*</span></label>
                        <select name="receta_id" class="form-select" id="selectReceta" required>
                            <option value="">— Seleccionar receta —</option>
                            <?php foreach ($recetas as $r): ?>
                                <option value="<?= $r['id'] ?>">
                                    <?= htmlspecialchars($r['producto_nombre']) ?>
                                    <?= $r['nombre'] ? '— ' . htmlspecialchars($r['nombre']) : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Cantidad producida <span style="color:var(--accent)">*</span></label>
                        <input type="number" name="cantidad" id="cantidadInput" class="form-control"
                               min="0.01" step="any" placeholder="0" required oninput="actualizarPreview()">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Notas (opcional)</label>
                        <input type="text" name="notas" class="form-control" placeholder="Ej: Producción matutina, lote especial...">
                    </div>

                    <!-- Preview de descuento de insumos -->
                    <div class="col-12" id="previewPanel" style="display:none;">
                        <div style="background:rgba(251,191,36,0.05);border:1px solid rgba(251,191,36,0.2);border-radius:12px;padding:16px;">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-flask me-2" style="color:var(--accent)"></i>
                                Insumos que se descontarán del stock
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr style="font-size:12px;color:var(--text-muted);">
                                            <th>Insumo</th>
                                            <th class="text-center">Por unidad</th>
                                            <th class="text-center">Total a descontar</th>
                                            <th class="text-center">Stock disponible</th>
                                            <th class="text-center">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyPreview"></tbody>
                                </table>
                            </div>
                            <div id="alertaStock" style="display:none;margin-top:10px;padding:10px 14px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:8px;font-size:13px;color:#ef4444;">
                                <i class="fas fa-triangle-exclamation me-2"></i>
                                <strong>Stock insuficiente</strong> para uno o más insumos. Puedes continuar, pero el stock quedará en negativo.
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center mt-2">
                        <button type="submit" class="btn-primary-custom px-5" id="btnRegistrar" disabled>
                            <i class="fas fa-industry me-2"></i>Registrar Producción
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const recetasData = <?= json_encode($recetasData) ?>;

document.getElementById('selectReceta').addEventListener('change', actualizarPreview);

function actualizarPreview() {
    const recetaId = document.getElementById('selectReceta').value;
    const cantidad = parseFloat(document.getElementById('cantidadInput').value) || 0;
    const panel    = document.getElementById('previewPanel');
    const btn      = document.getElementById('btnRegistrar');

    if (!recetaId || cantidad <= 0) {
        panel.style.display = 'none';
        btn.disabled = true;
        return;
    }

    const receta  = recetasData[recetaId];
    if (!receta || !receta.insumos.length) {
        panel.style.display = 'none';
        btn.disabled = false;
        return;
    }

    let hayInsuficiente = false;
    let html = '';

    receta.insumos.forEach(ins => {
        const necesario = ins.cantidad * cantidad;
        const suficiente = ins.stock >= necesario;
        if (!suficiente) hayInsuficiente = true;

        html += `<tr style="font-size:13px;">
            <td class="fw-600">${ins.nombre}</td>
            <td class="text-center">${ins.cantidad.toLocaleString('es-CO')} ${ins.unidad}</td>
            <td class="text-center fw-bold" style="color:#f59e0b;">
                ${necesario.toLocaleString('es-CO', {maximumFractionDigits:2})} ${ins.unidad}
            </td>
            <td class="text-center" style="color:${suficiente ? '#10b981' : '#ef4444'};">
                ${ins.stock.toLocaleString('es-CO', {maximumFractionDigits:0})} ${ins.unidad}
            </td>
            <td class="text-center">
                ${suficiente
                    ? '<span style="color:#10b981;font-size:12px;"><i class="fas fa-check-circle me-1"></i>OK</span>'
                    : '<span style="color:#ef4444;font-size:12px;"><i class="fas fa-triangle-exclamation me-1"></i>Insuficiente</span>'
                }
            </td>
        </tr>`;
    });

    document.getElementById('tbodyPreview').innerHTML = html;
    document.getElementById('alertaStock').style.display = hayInsuficiente ? 'block' : 'none';
    panel.style.display  = 'block';
    btn.disabled = false;
}
</script>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
