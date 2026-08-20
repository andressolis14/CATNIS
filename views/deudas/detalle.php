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
        <?php include __DIR__ . '/_tabla_abonos.php'; ?>
    </div>
</div>
<?php else: ?>
<div class="p-4 text-center mb-4" style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);border-radius:12px;">
    <i class="fas fa-circle-check fa-3x mb-2" style="color:var(--accent-green)"></i>
    <h5 style="color:var(--accent-green)">¡Deuda completamente pagada!</h5>
</div>
<div class="row g-3 mb-4">
    <div class="col-12">
        <?php include __DIR__ . '/_tabla_abonos.php'; ?>
    </div>
</div>
<?php endif; ?>

<!-- Modal Editar Abono -->
<div class="modal fade" id="modalEditarAbono" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:var(--bg-card);border:1px solid var(--border);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color:var(--text-main)">
                    <i class="fas fa-pen me-2" style="color:var(--accent)"></i>Editar Abono
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <form method="POST" action="<?= APP_URL ?>/deudas/editarAbono">
                    <input type="hidden" name="abono_id" id="edit_abono_id">
                    <input type="hidden" name="deuda_id" value="<?= $deuda['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Monto *</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:var(--bg-card2);border:1px solid var(--border);border-right:none;color:var(--text-muted);">$</span>
                            <input type="number" step="0.01" min="0.01" name="monto" id="edit_monto" class="form-control" style="border-left:none;" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Método de pago</label>
                        <select name="metodo_pago" id="edit_metodo" class="form-select">
                            <option value="efectivo">💵 Efectivo</option>
                            <option value="transferencia">🏦 Transferencia / Banco</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nota (opcional)</label>
                        <input type="text" name="nota" id="edit_nota" class="form-control" placeholder="Ej: Pago en efectivo">
                    </div>
                    <button type="submit" class="btn-primary-custom w-100">
                        <i class="fas fa-check me-2"></i>Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function abrirEditarAbono(abonoId, deudaId, monto, metodo, nota) {
    document.getElementById('edit_abono_id').value = abonoId;
    document.getElementById('edit_monto').value    = monto;
    document.getElementById('edit_metodo').value   = metodo;
    document.getElementById('edit_nota').value     = nota;
    new bootstrap.Modal(document.getElementById('modalEditarAbono')).show();
}
</script>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
