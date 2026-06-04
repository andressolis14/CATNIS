<?php
$pageTitle = 'Editar Venta #' . $venta['id'];
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header">
    <h4><i class="fas fa-edit me-2" style="color:var(--accent)"></i>Editar Venta #<?= $venta['id'] ?></h4>
    <p><a href="<?= APP_URL ?>/ventas" style="color:var(--accent);text-decoration:none;"><i
                class="fas fa-arrow-left me-1"></i>Volver a ventas</a></p>
</div>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger shadow-sm mb-4">
        <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<form method="POST" action="<?= APP_URL ?>/ventas/editar?id=<?= $venta['id'] ?>" id="ventaForm">
    <div class="row g-3">
        <!-- Panel izquierdo: Productos disponibles -->
        <div class="col-lg-8">
            <div class="pos-panel mb-3">
                <div class="pos-header d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-search me-2" style="color:var(--accent)"></i>Productos</span>
                    <input type="text" id="buscarProducto" class="form-control"
                        style="max-width:200px;font-size:13px;padding:6px 12px;" placeholder="Buscar...">
                </div>
                <div class="p-3">
                    <div class="row g-2" id="productosGrid">
                        <?php foreach ($productos as $p): ?>
                            <?php $esServicio = ($p['tipo'] ?? 'producto') === 'servicio'; ?>
                            <div class="col-6 col-md-4 product-item"
                                data-nombre="<?= strtolower(htmlspecialchars($p['nombre'])) ?>">
                                <div class="product-card-mini"
                                    onclick="agregarAlCarrito(<?= $p['id'] ?>, '<?= addslashes($p['nombre']) ?>', <?= (int) $p['precio_venta'] ?>, <?= $esServicio ? 99999 : $p['stock'] ?>)">
                                    <div style="font-size:13px;font-weight:600;margin-bottom:4px;">
                                        <?= htmlspecialchars($p['nombre']) ?>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-green fw-bold">$<?= number_format($p['precio_venta'], 0, ',', '.') ?></span>
                                        <?php if ($esServicio): ?>
                                            <span style="font-size:10px;background:rgba(139,92,246,0.2);color:#a78bfa;padding:2px 6px;border-radius:10px;">Servicio</span>
                                        <?php else: ?>
                                            <span style="font-size:11px;color:var(--text-dim);">Stock: <?= $p['stock'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel derecho: Carrito -->
        <div class="col-lg-4">
            <div class="pos-panel mb-3">
                <div class="pos-header"><i class="fas fa-shopping-bag me-2" style="color:var(--accent)"></i>Resumen de
                    Venta
                </div>
                <div class="p-3">
                    <div id="cartItems"></div>

                    <div class="cart-total text-center mb-3" id="totalBox">
                        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">TOTAL A COBRAR</div>
                        <div style="font-size:24px;">$<span
                                id="totalAmount"><?= number_format($venta['total'], 0, ',', '.') ?></span></div>
                    </div>

                    <!-- Fecha de venta -->
                    <div class="mb-3">
                        <label class="form-label">Fecha de Venta</label>
                        <input type="date" name="fecha" class="form-control"
                            value="<?= date('Y-m-d', strtotime($venta['fecha'])) ?>" required>
                    </div>

                    <!-- Tipo de venta -->
                    <div class="mb-3">
                        <label class="form-label">Tipo de Venta</label>
                        <select name="tipo" class="form-select" id="tipoVenta">
                            <option value="contado" <?= $venta['tipo'] === 'contado' ? 'selected' : '' ?>>💵 Contado
                            </option>
                            <option value="credito" <?= $venta['tipo'] === 'credito' ? 'selected' : '' ?>>📋 Crédito
                            </option>
                        </select>
                    </div>

                    <!-- Método de Pago -->
                    <div class="mb-3" id="metodoPagoDiv"
                        style="<?= $venta['tipo'] === 'credito' ? 'display:none' : '' ?>">
                        <label class="form-label">Método de Pago</label>
                        <select name="metodo_pago" class="form-select">
                            <option value="efectivo" <?= $venta['metodo_pago'] === 'efectivo' ? 'selected' : '' ?>>💵
                                Efectivo</option>
                            <option value="transferencia" <?= $venta['metodo_pago'] === 'transferencia' ? 'selected' : '' ?>>🏦 Transferencia / Banco</option>
                            <option value="otros" <?= $venta['metodo_pago'] === 'otros' ? 'selected' : '' ?>>📱 Otros
                            </option>
                        </select>
                    </div>

                    <!-- Cliente -->
                    <div class="mb-3" id="clienteDiv">
                        <label class="form-label">Cliente
                            <?= $venta['tipo'] === 'credito' ? '<span style="color:var(--accent-red)">*</span>' : '' ?></label>
                        <select name="cliente_id" class="form-select" id="clienteSelect" <?= $venta['tipo'] === 'credito' ? 'required' : '' ?>>
                            <option value="">— Cliente General —</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $venta['cliente_id'] == $c['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notas (opcional)</label>
                        <input type="text" name="notas" class="form-control"
                            value="<?= htmlspecialchars($venta['notas']) ?>" placeholder="Observaciones...">
                    </div>

                    <div id="hiddenInputs"></div>

                    <button type="submit" class="btn-primary-custom w-100" id="btnVender">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>

                    <p class="text-center mt-3 small text-muted">
                        <i class="fas fa-info-circle me-1"></i>Al guardar, el stock se ajustará automáticamente según
                        los cambios realizados.
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
$extraJs = '
<script>
// Inicializar carrito con datos existentes
let carrito = {};
';

foreach ($detalles as $d) {
    $extraJs .= "carrito[{$d['producto_id']}] = {
        id: {$d['producto_id']},
        nombre: '" . addslashes($d['producto']) . "',
        precio: " . (float) $d['precio_unitario'] . ",
        cantidad: " . (int) $d['cantidad'] . ",
        stock: 99999
    };\n";
}

$extraJs .= '
document.addEventListener("DOMContentLoaded", () => {
    renderCart();
});

function agregarAlCarrito(id, nombre, precio, stock) {
    if (carrito[id]) {
        carrito[id].cantidad++;
    } else {
        carrito[id] = { id, nombre, precio, cantidad: 1, stock };
    }
    renderCart();
}

function cambiarCantidad(id, delta) {
    if (!carrito[id]) return;
    carrito[id].cantidad += delta;
    if (carrito[id].cantidad <= 0) delete carrito[id];
    renderCart();
}

function actualizarTotal(id, nuevoTotal) {
    if (!carrito[id]) return;
    const total = Math.max(0, parseInt(nuevoTotal) || 0);
    carrito[id].precio = carrito[id].cantidad > 0 ? total / carrito[id].cantidad : 0;
    renderCart();
}

function renderCart() {
    const items = Object.values(carrito);
    const cartDiv = document.getElementById("cartItems");
    const totalBox = document.getElementById("totalBox");
    const btnVender = document.getElementById("btnVender");
    const hiddenInputs = document.getElementById("hiddenInputs");

    if (items.length === 0) {
        cartDiv.innerHTML = `<div class="text-center py-3" style="color:var(--text-dim);font-size:13px;"><i class="fas fa-cart-plus fa-2x mb-2 d-block"></i>La venta no tiene productos.</div>`;
        totalBox.style.display = "none";
        btnVender.disabled = true;
        hiddenInputs.innerHTML = "";
        return;
    }

    let total = 0;
    let html = "";
    let inputs = "";
    items.forEach((item, i) => {
        const sub = Math.round(item.precio * item.cantidad);
        total += sub;
        html += `<div class="cart-item d-flex align-items-center justify-content-between mb-3 p-2" style="background:rgba(255,255,255,0.03);border-radius:10px;border:1px solid rgba(255,255,255,0.05);">
            <div style="flex:1;min-width:0;margin-right:8px;">
                <div style="font-size:13px;font-weight:600;">${item.nombre}</div>
                <div style="font-size:11px;color:var(--text-muted);">$${item.precio.toLocaleString(\'es-CO\')} c/u</div>
            </div>
            <div class="d-flex align-items-center gap-2" style="flex-shrink:0;">
                <button type="button" onclick="cambiarCantidad(${item.id},-1)" class="btn-cart-sm" style="background:rgba(239,68,68,0.15);color:#ef4444;border:none;">−</button>
                <span class="fw-bold px-1">${item.cantidad}</span>
                <button type="button" onclick="cambiarCantidad(${item.id},1)" class="btn-cart-sm" style="background:rgba(16,185,129,0.15);color:#10b981;border:none;">+</button>
                <div style="min-width:70px;text-align:right;" title="Clic para editar total">
                    $<input type="number" min="0" step="100"
                        value="${sub}"
                        onchange="actualizarTotal(${item.id}, this.value)"
                        style="width:65px;background:transparent;border:none;border-bottom:1px dashed var(--accent-green);color:var(--accent-green);font-weight:700;font-size:13px;padding:0 2px;text-align:right;">
                </div>
            </div>
        </div>`;
        inputs += `<input type="hidden" name="items[${i}][producto_id]" value="${item.id}">`;
        inputs += `<input type="hidden" name="items[${i}][cantidad]" value="${item.cantidad}">`;
        inputs += `<input type="hidden" name="items[${i}][precio_unitario]" value="${item.precio}">`;
    });

    cartDiv.innerHTML = html;
    hiddenInputs.innerHTML = inputs;
    document.getElementById("totalAmount").textContent = total.toLocaleString(\'es-CO\');
    totalBox.style.cssText = "display:block!important";
    btnVender.disabled = false;
}

// Buscar producto
document.getElementById("buscarProducto").addEventListener("input", function() {
    const term = this.value.toLowerCase();
    document.querySelectorAll(".product-item").forEach(el => {
        el.style.display = el.dataset.nombre.includes(term) ? "" : "none";
    });
});

// Estilos rápidos para botones miniatura
const style = document.createElement(\'style\');
style.textContent = \'.btn-cart-sm { width:26px; height:26px; border-radius:6px; cursor:pointer; font-weight:bold; display:flex; align-items:center; justify-content:center; }\';
document.head.appendChild(style);

document.getElementById("tipoVenta").addEventListener("change", function() {
    const clienteSelect = document.getElementById("clienteSelect");
    const metodoDiv = document.getElementById("metodoPagoDiv");
    if (this.value === "credito") {
        clienteSelect.required = true;
        clienteSelect.parentElement.querySelector(".form-label").innerHTML = "Cliente <span style=\'color:var(--accent-red)\'>*</span>";
        metodoDiv.style.display = "none";
    } else {
        clienteSelect.required = false;
        clienteSelect.parentElement.querySelector(".form-label").textContent = "Cliente";
        metodoDiv.style.display = "block";
    }
});
</script>';
require_once APP_ROOT . '/views/layout/footer.php';
?>