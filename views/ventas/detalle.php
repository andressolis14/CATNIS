<?php
$pageTitle = 'Detalle de Venta #' . $venta['id'];
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-1"><i class="fas fa-receipt me-2" style="color:var(--accent)"></i>Venta
            #<?= str_pad($venta['id'], 5, '0', STR_PAD_LEFT) ?></h4>
        <p class="m-0"><a href="<?= APP_URL ?>/ventas"
                style="color:var(--text-muted);text-decoration:none;font-size:13px;"><i
                    class="fas fa-arrow-left me-1"></i>Volver a historial</a></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= APP_URL ?>/ventas/crear" class="btn-primary-custom">
            <i class="fas fa-plus me-2"></i>Nueva Venta
        </a>
        <a href="<?= APP_URL ?>/ventas/recibo?id=<?= $venta['id'] ?>" class="btn-primary-custom"
            style="background:var(--bg-card2); border:1px solid var(--border); color:var(--text-main)" target="_blank">
            <i class="fas fa-print me-2"></i>Ver Recibo
        </a>
        <a href="<?= $waLink ?>" class="btn-primary-custom" style="background:#25d366;" target="_blank">
            <i class="fab fa-whatsapp me-2"></i>WhatsApp
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:var(--accent)"><i class="fas fa-info-circle me-2"></i>Información</h6>
            <table style="font-size:14px;width:100%">
                <tr>
                    <td style="color:var(--text-muted);padding:6px 0;">Fecha:</td>
                    <td><?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></td>
                </tr>
                <tr>
                    <td style="color:var(--text-muted);padding:6px 0;">Cliente:</td>
                    <td><?= htmlspecialchars($venta['cliente_nombre'] ?? 'General') ?></td>
                </tr>
                <tr>
                    <td style="color:var(--text-muted);padding:6px 0;">Vendedor:</td>
                    <td><?= htmlspecialchars($venta['vendedor']) ?></td>
                </tr>
                <tr>
                    <td style="color:var(--text-muted);padding:6px 0;">Tipo:</td>
                    <td><span class="badge-<?= $venta['tipo'] ?>"><?= ucfirst($venta['tipo']) ?></span></td>
                </tr>
                <tr>
                    <td style="color:var(--text-muted);padding:6px 0;">Medio de Pago:</td>
                    <td>
                        <?php if ($venta['metodo_pago'] === 'transferencia'): ?>
                            <span class="text-blue fw-bold"><i class="fas fa-university me-1"></i>Transferencia /
                                Banco</span>
                        <?php else: ?>
                            <span class="text-green fw-bold"><i class="fas fa-money-bill-wave me-1"></i>Efectivo</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td style="color:var(--text-muted);padding:6px 0;">Estado:</td>
                    <td><span class="badge-<?= $venta['estado'] ?>"><?= ucfirst($venta['estado']) ?></span></td>
                </tr>
                <?php if ($venta['notas']): ?>
                    <tr>
                        <td style="color:var(--text-muted);padding:6px 0;">Notas:</td>
                        <td><?= htmlspecialchars($venta['notas']) ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
        <div class="cart-total text-center">
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">TOTAL DE LA VENTA</div>
            <div>$<?= number_format($venta['total'], 2) ?></div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="table-card">
            <div class="table-card-header">
                <h6><i class="fas fa-boxes-stacked me-2"></i>Productos Vendidos</h6>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-end">Precio</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $d): ?>
                        <tr>
                            <td><?= htmlspecialchars($d['producto']) ?></td>
                            <td class="text-center"><?= $d['cantidad'] ?></td>
                            <td class="text-end">$<?= number_format($d['precio_unitario'], 0, ',', '.') ?></td>
                            <td class="text-end fw-bold text-green">$<?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>