<?php
$pageTitle = 'Deuda: ' . htmlspecialchars($deuda['cliente']);
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header">
    <h4><i class="fas fa-hand-holding-dollar me-2" style="color:var(--accent)"></i>Deuda de <?= htmlspecialchars($deuda['cliente']) ?></h4>
    <p><a href="<?= APP_URL ?>/deudas" style="color:var(--accent);text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Volver</a></p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card"><div class="stat-icon icon-amber"><i class="fas fa-sack-dollar"></i></div>
        <div class="stat-value text-amber">$<?= number_format($deuda['total'], 2) ?></div><div class="stat-label">Total Original</div></div>
    </div>
    <div class="col-md-4">
        <div class="stat-card"><div class="stat-icon icon-green"><i class="fas fa-circle-check"></i></div>
        <div class="stat-value text-green">$<?= number_format($deuda['abonado'], 2) ?></div><div class="stat-label">Total Abonado</div></div>
    </div>
    <div class="col-md-4">
        <div class="stat-card"><div class="stat-icon icon-red"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-value text-red">$<?= number_format($deuda['saldo'], 2) ?></div><div class="stat-label">Saldo Pendiente</div></div>
    </div>
</div>

<?php if ($deuda['estado'] !== 'pagada'): ?>
<div class="row g-3 mb-4">
    <div class="col-lg-5">
        <div class="form-card">
            <h6 class="mb-3" style="color:var(--accent)"><i class="fas fa-plus-circle me-2"></i>Registrar Abono</h6>
            <form method="POST" action="<?= APP_URL ?>/deudas/abonar">
                <input type="hidden" name="deuda_id" value="<?= $deuda['id'] ?>">
                <div class="mb-3">
                    <label class="form-label">Monto del abono *</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:var(--bg-card2);border:1px solid var(--border);border-right:none;color:var(--text-muted);">$</span>
                        <input type="number" step="0.01" min="0.01" max="<?= $deuda['saldo'] ?>" name="monto" class="form-control" style="border-left:none;" placeholder="0.00" required>
                    </div>
                    <div style="font-size:11px;color:var(--text-dim);margin-top:4px;">Máximo: $<?= number_format($deuda['saldo'], 2) ?></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Abonar a:</label>
                    <select name="metodo_pago" class="form-select">
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="transferencia">🏦 Transferencia / Banco</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nota (opcional)</label>
                    <input type="text" name="nota" class="form-control" placeholder="Ej: Pago en efectivo">
                </div>
                <button type="submit" class="btn-primary-custom w-100">
                    <i class="fas fa-check me-2"></i>Registrar Abono
                </button>
            </form>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="table-card">
            <div class="table-card-header"><h6><i class="fas fa-list-check me-2"></i>Abonos Registrados</h6></div>
            <?php if (empty($abonos)): ?>
                <div class="p-3 text-center" style="color:var(--text-dim);font-size:13px;">Sin abonos aún.</div>
            <?php else: ?>
                <table class="table">
                    <thead><tr><th>Fecha</th><th>Método</th><th class="text-end">Monto</th><th>Nota</th></tr></thead>
                    <tbody>
                    <?php foreach ($abonos as $a): ?>
                        <tr>
                            <td style="font-size:13px"><?= date('d/m/Y H:i', strtotime($a['fecha'])) ?></td>
                            <td style="font-size:12px;">
                                <?php if($a['metodo_pago'] === 'transferencia'): ?>
                                    <span class="badge" style="background:rgba(59,130,246,0.15);color:#3b82f6;"><i class="fas fa-university me-1"></i>Banco</span>
                                <?php else: ?>
                                    <span class="badge" style="background:rgba(16,185,129,0.15);color:#10b981;"><i class="fas fa-money-bill-wave me-1"></i>Efectivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-green fw-bold">$<?= number_format($a['monto'], 2) ?></td>
                            <td style="color:var(--text-muted);font-size:13px"><?= htmlspecialchars($a['nota'] ?: '—') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="p-4 text-center" style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);border-radius:12px;margin-bottom:24px;">
    <i class="fas fa-circle-check fa-3x mb-2" style="color:var(--accent-green)"></i>
    <h5 style="color:var(--accent-green)">¡Deuda completamente pagada!</h5>
</div>
<?php endif; ?>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
