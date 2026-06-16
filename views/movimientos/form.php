<?php
$pageTitle = 'Registrar Movimiento';
require_once APP_ROOT . '/views/layout/header.php';
$tipos = [
    'entrada' => ['label' => 'Entrada (Compra)',        'icon' => 'fa-arrow-down',  'color' => '#10b981'],
    'salida'  => ['label' => 'Salida (Uso producción)', 'icon' => 'fa-arrow-up',    'color' => '#f59e0b'],
    'merma'   => ['label' => 'Merma / Desperdicio',     'icon' => 'fa-trash',       'color' => '#ef4444'],
    'ajuste'  => ['label' => 'Ajuste de inventario',    'icon' => 'fa-sliders',     'color' => '#3b82f6'],
];
?>

<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold"><i class="fas fa-arrows-up-down me-2" style="color:var(--accent)"></i>Registrar Movimiento</h4>
        <p><a href="<?= APP_URL ?>/movimientos" style="color:var(--accent);text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Volver a movimientos</a></p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="form-card">
            <form method="POST" action="<?= APP_URL ?>/movimientos/crear">
                <div class="row g-3">

                    <!-- Tipo de movimiento -->
                    <div class="col-12">
                        <label class="form-label">Tipo de movimiento <span style="color:var(--accent)">*</span></label>
                        <div class="row g-2">
                            <?php foreach ($tipos as $val => $t): ?>
                            <div class="col-6">
                                <label style="cursor:pointer;display:block;">
                                    <input type="radio" name="tipo" value="<?= $val ?>" class="d-none tipo-radio" <?= $val === 'entrada' ? 'checked' : '' ?>>
                                    <div class="tipo-btn" data-tipo="<?= $val ?>" style="border:2px solid var(--border);border-radius:10px;padding:10px 12px;transition:.2s;<?= $val === 'entrada' ? "border-color:{$t['color']};background:rgba(16,185,129,0.08);" : '' ?>">
                                        <i class="fas <?= $t['icon'] ?> me-2" style="color:<?= $t['color'] ?>"></i>
                                        <span style="font-size:13px;font-weight:600;"><?= $t['label'] ?></span>
                                    </div>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Insumo -->
                    <div class="col-12">
                        <label class="form-label">Insumo <span style="color:var(--accent)">*</span></label>
                        <select name="insumo_id" class="form-select" required id="selectInsumo">
                            <option value="">— Seleccionar insumo —</option>
                            <?php foreach ($insumos as $ins): ?>
                                <option value="<?= $ins['id'] ?>"
                                    data-stock="<?= (int)$ins['stock'] ?>"
                                    data-unidad="<?= htmlspecialchars($ins['unidad_medida']) ?>"
                                    <?= $insumoSeleccionado === $ins['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ins['nombre']) ?>
                                    (Stock: <?= number_format((int)$ins['stock'], 0, ',', '.') ?> <?= $ins['unidad_medida'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div id="stockInfo" style="font-size:12px;color:var(--text-muted);margin-top:4px;"></div>
                    </div>

                    <!-- Cantidad -->
                    <div class="col-md-6">
                        <label class="form-label">Cantidad <span style="color:var(--accent)">*</span></label>
                        <div class="input-group">
                            <input type="number" name="cantidad" class="form-control" min="0.01" step="any" placeholder="0" required>
                            <span class="input-group-text" id="labelUnidad">unid.</span>
                        </div>
                    </div>

                    <!-- Fecha -->
                    <div class="col-md-6">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>

                    <!-- Descripción -->
                    <div class="col-12">
                        <label class="form-label">Motivo / Descripción</label>
                        <input type="text" name="descripcion" class="form-control"
                               placeholder="Ej: Compra bulto 25kg, Producción del día, Vencimiento...">
                    </div>

                    <div class="col-12 text-center mt-2">
                        <button type="submit" class="btn-primary-custom px-5">
                            <i class="fas fa-check me-2"></i>Registrar Movimiento
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const tipoColors = {
    entrada: '#10b981', salida: '#f59e0b', merma: '#ef4444', ajuste: '#3b82f6'
};

document.querySelectorAll('.tipo-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.tipo-btn').forEach(btn => {
            btn.style.borderColor = 'var(--border)';
            btn.style.background = '';
        });
        const btn = document.querySelector(`.tipo-btn[data-tipo="${this.value}"]`);
        const color = tipoColors[this.value];
        btn.style.borderColor = color;
        btn.style.background = `${color}15`;
    });
});

document.getElementById('selectInsumo').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const unidad = opt.dataset.unidad || 'unid.';
    const stock = opt.dataset.stock || '0';
    document.getElementById('labelUnidad').textContent = unidad;
    document.getElementById('stockInfo').textContent = stock > 0
        ? `Stock actual: ${parseInt(stock).toLocaleString('es-CO')} ${unidad}`
        : 'Sin stock disponible';
});

// Trigger para insumo preseleccionado
if (document.getElementById('selectInsumo').value) {
    document.getElementById('selectInsumo').dispatchEvent(new Event('change'));
}
</script>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
