<?php
$pageTitle = 'Historial de Ventas';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h4><i class="fas fa-shopping-cart me-2" style="color:var(--accent)"></i>Ventas</h4>
        <p>Historial de todas las ventas</p>
    </div>
    <a href="<?= APP_URL ?>/ventas/crear" class="btn-primary-custom">
        <i class="fas fa-plus me-2"></i>Nueva Venta
    </a>
</div>

<div class="table-card">
    <?php if (empty($ventas)): ?>
        <div class="p-5 text-center">
            <i class="fas fa-receipt fa-3x mb-3" style="color:var(--text-dim)"></i>
            <h6 style="color:var(--text-muted)">Sin ventas registradas</h6>
            <a href="<?= APP_URL ?>/ventas/crear" class="btn-primary-custom mt-3 d-inline-block">Registrar primera venta</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>#</th><th>Fecha</th><th>Cliente</th><th>Tipo</th><th>Pago</th><th class="text-end">Total</th><th>Estado</th><th class="text-end">Acción</th></tr></thead>
                <tbody>
                <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td class="text-dim"><?= $v['id'] ?></td>
                        <td class="text-muted" style="font-size:13px"><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                        <td class="fw-600 text-main"><?= htmlspecialchars($v['cliente_nombre'] ?? 'General') ?></td>
                        <td><span class="badge-<?= $v['tipo'] ?>"><?= ucfirst($v['tipo'] === 'contado' ? 'Cont.' : 'Créd.') ?></span></td>
                        <td>
                            <?php if($v['metodo_pago'] === 'transferencia'): ?>
                                <span class="text-blue" title="Transferencia/Banco"><i class="fas fa-university"></i></span>
                            <?php elseif($v['metodo_pago'] === 'efectivo'): ?>
                                <span class="text-green" title="Efectivo"><i class="fas fa-money-bill-wave"></i></span>
                            <?php else: ?>
                                <span class="text-dim" title="Otros"><i class="fas fa-wallet"></i></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end fw-bold text-green">$<?= number_format($v['total'], 0, ',', '.') ?></td>
                        <td><span class="badge-<?= $v['estado'] ?>"><?= ucfirst($v['estado']) ?></span></td>
                        <td class="text-end">
                            <a href="<?= APP_URL ?>/ventas/recibo?id=<?= $v['id'] ?>" class="btn-sm-icon me-1" style="background:rgba(245,158,11,0.1);color:var(--accent);" title="Ver Recibo" target="_blank">
                                <i class="fas fa-receipt"></i>
                            </a>
                            <a href="<?= APP_URL ?>/ventas/detalle?id=<?= $v['id'] ?>" class="btn-sm-icon" style="background:rgba(59,130,246,0.15);color:var(--accent-blue);" title="Ver Detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
