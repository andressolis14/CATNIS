<?php
$pageTitle = 'Cuentas por Cobrar';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h4><i class="fas fa-file-invoice-dollar me-2" style="color:var(--accent)"></i>Cuentas por Cobrar</h4>
        <p>Ventas al crédito (fiadas) y sus saldos pendientes</p>
    </div>
</div>

<div class="table-card">
    <?php if (empty($deudas)): ?>
        <div class="p-5 text-center">
            <i class="fas fa-check-double fa-3x mb-3" style="color:var(--accent-green)"></i>
            <h6 style="color:var(--text-muted)">¡Todo pagado! Sin deudas pendientes.</h6>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Fecha</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Abonado</th>
                        <th class="text-end">Saldo</th>
                        <th>Estado</th>
                        <th class="text-end">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deudas as $d): ?>
                        <td class="fw-600 text-main">
                            <?= htmlspecialchars($d['cliente']) ?>
                            <?php if ($d['dias_antiguedad'] >= 7 && $d['estado'] !== 'pagada'): ?>
                                <i class="fas fa-clock ms-1" style="color:#ef4444; font-size:12px;" title="Vencida (+7 días)"></i>
                            <?php endif; ?>
                        </td>
                        <td class="text-dim" style="font-size:13px"><?= htmlspecialchars($d['telefono'] ?: '—') ?></td>
                        <td class="text-muted" style="font-size:13px">
                            <?= date('d/m/Y', strtotime($d['fecha'])) ?>
                            <div style="font-size:10px; color:rgba(255,255,255,0.2)"><?= $d['dias_antiguedad'] ?> d</div>
                        </td>
                        <td class="text-end text-main">$<?= number_format($d['total'], 0, ',', '.') ?></td>
                        <td class="text-end text-green">$<?= number_format($d['abonado'], 0, ',', '.') ?></td>
                        <td class="text-end fw-bold text-red">$<?= number_format($d['saldo'], 0, ',', '.') ?></td>
                        <td><span class="badge-<?= $d['estado'] ?>"><?= ucfirst($d['estado']) ?></span></td>
                        <td class="text-end">
                            <?php if ($d['estado'] !== 'pagada' && !empty($d['telefono'])): ?>
                                <a href="<?= $d['wa_link'] ?>" class="btn-sm-icon me-1"
                                    style="background:rgba(37,211,102,0.15);color:#25d366;" title="Cobrar por WhatsApp"
                                    target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            <?php endif; ?>
                            <a href="<?= APP_URL ?>/deudas/detalle?id=<?= $d['id'] ?>" class="btn-sm-icon"
                                style="background:rgba(245,158,11,0.15);color:var(--accent);" title="Ver / Abonar">
                                <i class="fas fa-hand-holding-dollar"></i>
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