<?php
$pageTitle = 'Nueva Venta';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header">
    <h4><i class="fas fa-cash-register me-2" style="color:var(--accent)"></i>Nueva Venta</h4>
    <p><a href="<?= APP_URL ?>/ventas" style="color:var(--accent);text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Volver a ventas</a></p>
</div>

<form method="POST" action="<?= APP_URL ?>/ventas/crear" id="ventaForm">
<div class="row g-3">
    <!-- Panel izquierdo: Productos disponibles -->
    <div class="col-lg-8">
        <div class="pos-panel mb-3">
            <div class="pos-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-search me-2" style="color:var(--accent)"></i>Productos</span>
                <input type="text" id="buscarProducto" class="form-control" style="max-width:200px;font-size:13px;padding:6px 12px;" placeholder="Buscar...">
            </div>
            <div class="p-3">
                <div class="row g-2" id="productosGrid">
                <?php foreach ($productos as $p): ?>
                    <?php if ($p['stock'] > 0): ?>
                    <div class="col-6 col-md-4 product-item" data-nombre="<?= strtolower(htmlspecialchars($p['nombre'])) ?>">
                        <div class="product-card-mini" onclick="agregarAlCarrito(<?= $p['id'] ?>, '<?= addslashes($p['nombre']) ?>', <?= (int)$p['precio_venta'] ?>, <?= $p['stock'] ?>)">
                            <div style="font-size:13px;font-weight:600;margin-bottom:4px;"><?= htmlspecialchars($p['nombre']) ?></div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-green fw-bold">$<?= number_format($p['precio_venta'], 0, ',', '.') ?></span>
                                <span style="font-size:11px;color:var(--text-dim);">Stock: <?= $p['stock'] ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                </div>
                <?php if (empty(array_filter($productos, fn($p) => $p['stock'] > 0))): ?>
                    <div class="text-center py-3" style="color:var(--text-dim);font-size:13px;">Sin productos con stock disponible.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Panel derecho: Carrito -->
    <div class="col-lg-4">
        <div class="pos-panel mb-3">
            <div class="pos-header"><i class="fas fa-shopping-bag me-2" style="color:var(--accent)"></i>Carrito</div>
            <div class="p-3">
                <div id="cartItems">
                    <div id="cartEmpty" class="text-center py-3" style="color:var(--text-dim);font-size:13px;">
                        <i class="fas fa-cart-plus fa-2x mb-2 d-block"></i>Toca un producto para agregarlo
                    </div>
                </div>

                <div class="cart-total text-center mb-3" id="totalBox" style="display:none!important;">
                    <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">TOTAL A COBRAR</div>
                    <div style="font-size:24px;">$<span id="totalAmount">0</span></div>
                </div>

                <!-- Fecha de venta -->
                <div class="mb-3">
                    <label class="form-label">Fecha de Venta</label>
                    <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>

                <!-- Tipo de venta -->
                <div class="mb-3">
                    <label class="form-label">Tipo de Venta</label>
                    <select name="tipo" class="form-select" id="tipoVenta">
                        <option value="contado">💵 Contado</option>
                        <option value="credito">📋 Crédito (Fiado)</option>
                    </select>
                </div>

                <!-- Método de Pago -->
                <div class="mb-3" id="metodoPagoDiv">
                    <label class="form-label">Método de Pago</label>
                    <select name="metodo_pago" class="form-select">
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="transferencia">🏦 Transferencia / Banco</option>
                        <option value="otros">📱 Otros</option>
                    </select>
                </div>

                <!-- Cliente (requerido para crédito) -->
                <div class="mb-3" id="clienteDiv">
                    <label class="form-label">Cliente</label>
                    <select name="cliente_id" class="form-select" id="clienteSelect">
                        <option value="">— Cliente General —</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notas (opcional)</label>
                    <input type="text" name="notas" class="form-control" placeholder="Observaciones...">
                </div>

                <!-- Items ocultos del carrito -->
                <div id="hiddenInputs"></div>

                <button type="submit" class="btn-primary-custom w-100" id="btnVender" disabled>
                    <i class="fas fa-check me-2"></i>Registrar Venta
                </button>
            </div>
        </div>
    </div>
</div>
</form>

<?php
$extraJs = '
<script>
let carrito = {};

function agregarAlCarrito(id, nombre, precio, stock) {
    if (carrito[id]) {
        if (carrito[id].cantidad >= stock) {
            alert("Stock insuficiente para: " + nombre);
            return;
        }
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
    else if (carrito[id].cantidad > carrito[id].stock) carrito[id].cantidad = carrito[id].stock;
    renderCart();
}

function renderCart() {
    const items = Object.values(carrito);
    const cartDiv = document.getElementById("cartItems");
    const emptyDiv = document.getElementById("cartEmpty");
    const totalBox = document.getElementById("totalBox");
    const btnVender = document.getElementById("btnVender");
    const hiddenInputs = document.getElementById("hiddenInputs");

    if (items.length === 0) {
        cartDiv.innerHTML = `<div id="cartEmpty" class="text-center py-3" style="color:var(--text-dim);font-size:13px;"><i class="fas fa-cart-plus fa-2x mb-2 d-block"></i>Toca un producto para agregarlo</div>`;
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
        html += `<div class="cart-item d-flex align-items-center justify-content-between">
            <div>
                <div style="font-size:13px;font-weight:600;">${item.nombre}</div>
                <div style="font-size:12px;color:var(--text-muted);">$${item.precio.toLocaleString(\'es-CO\')} c/u</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" onclick="cambiarCantidad(${item.id},-1)" style="background:rgba(239,68,68,0.2);color:#ef4444;border:none;border-radius:6px;width:26px;height:26px;cursor:pointer;font-weight:bold;">−</button>
                <span style="font-size:14px;font-weight:700;min-width:20px;text-align:center;">${item.cantidad}</span>
                <button type="button" onclick="cambiarCantidad(${item.id},1)" style="background:rgba(16,185,129,0.2);color:#10b981;border:none;border-radius:6px;width:26px;height:26px;cursor:pointer;font-weight:bold;">+</button>
                <span style="min-width:55px;text-align:right;color:var(--accent-green);font-weight:700;font-size:13px;">$${sub.toLocaleString(\'es-CO\')}</span>
            </div>
        </div>`;
        inputs += `<input type="hidden" name="items[${i}][producto_id]" value="${item.id}">`;
        inputs += `<input type="hidden" name="items[${i}][cantidad]" value="${item.cantidad}">`;
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

// Validar crédito requiere cliente
document.getElementById("tipoVenta").addEventListener("change", function() {
    const clienteSelect = document.getElementById("clienteSelect");
    if (this.value === "credito") {
        clienteSelect.required = true;
        clienteSelect.parentElement.querySelector(".form-label").innerHTML = "Cliente <span style=\'color:var(--accent-red)\'>*</span>";
    } else {
        clienteSelect.required = false;
        clienteSelect.parentElement.querySelector(".form-label").textContent = "Cliente";
    }
});
</script>';
require_once APP_ROOT . '/views/layout/footer.php';
?>
