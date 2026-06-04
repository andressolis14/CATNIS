<?php
$pageTitle = 'Historial de Arqueos';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold"><i class="fas fa-history me-2" style="color:var(--accent)"></i>Historial de Turnos</h4>
        <p class="text-muted" style="font-size: 14px;">Revisa los cierres de caja y posibles descuadres.</p>
    </div>
    <a href="<?= APP_URL ?>/caja" class="btn btn-primary-custom">
        <i class="fas fa-arrow-left me-1"></i> Volver al Monitor
    </a>
</div>

<div class="table-card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Apertura / Cierre</th>
                    <th>Usuario</th>
                    <th class="text-end">Base (Ef/Ba)</th>
                    <th class="text-end">Ventas (Efe/Ba)</th>
                    <th class="text-end">Esperado Total</th>
                    <th class="text-end">Real (Efe/Ba)</th>
                    <th class="text-center">Diferencia</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sesiones as $s): ?>
                    <tr>
                        <td class="small">
                            <i class="fas fa-sign-in-alt text-success me-1"></i> <span
                                class="fw-bold"><?= date('d/m/y H:i', strtotime($s['fecha_apertura'])) ?></span><br>
                            <?php if ($s['fecha_cierre']): ?>
                                <i class="fas fa-sign-out-alt text-danger me-1"></i>
                                <?= date('d/m/y H:i', strtotime($s['fecha_cierre'])) ?>
                            <?php else: ?>
                                <span class="badge bg-success bg-opacity-10 text-success p-1">ACTIVO</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($s['abierto_por']) ?></td>
                        <td class="text-end small">
                            $<?= number_format($s['monto_inicial_efectivo'], 0) ?><br>
                            <span class="text-muted">$<?= number_format($s['monto_inicial_banco'], 0) ?></span>
                        </td>
                        <td class="text-end small">
                            $<?= number_format($s['monto_esperado_efectivo'] - $s['monto_inicial_efectivo'], 0) ?> (Ef)<br>
                            <span
                                class="text-muted">$<?= number_format($s['monto_esperado_banco'] - $s['monto_inicial_banco'], 0) ?>
                                (Tr)</span><br>
                            <span class="text-purple fw-bold">Fiado:
                                $<?= number_format($s['ventas_credito'] ?? 0, 0) ?></span>
                            <span class="text-danger small fw-bold">(Deben:
                                $<?= number_format($s['saldo_credito'] ?? 0, 0) ?>)</span>
                        </td>
                        <td class="text-end fw-bold">
                            $<?= number_format($s['monto_esperado_efectivo'] + $s['monto_esperado_banco'], 0, ',', '.') ?>
                        </td>
                        <td class="text-end small">
                            $<?= number_format($s['monto_real_efectivo'], 0) ?><br>
                            <span class="text-muted">$<?= number_format($s['monto_real_banco'], 0) ?></span>
                        </td>
                        <td class="text-center">
                            <?php
                            $dif = $s['diferencia_efectivo'] + $s['diferencia_banco'];
                            if ($s['estado'] === 'abierta'): ?>
                                ---
                            <?php elseif ($dif == 0): ?>
                                <span class="text-success"><i class="fas fa-check-circle me-1"></i>Exacto</span>
                            <?php elseif ($dif > 0): ?>
                                <span class="text-primary fw-bold">+$<?= number_format($dif, 0) ?></span>
                            <?php else: ?>
                                <span class="text-danger fw-bold">$<?= number_format($dif, 0) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= $s['estado'] === 'abierta' ? 'bg-success' : 'bg-secondary' ?> p-2">
                                <?= strtoupper($s['estado']) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php if ($s['estado'] === 'cerrada'): ?>
                                <div class="d-flex gap-1 justify-content-center">
                                    <button class="btn btn-sm btn-outline-warning btn-editar-arqueo"
                                        data-id="<?= $s['id'] ?>"
                                        data-ef="<?= (float)($s['monto_real_efectivo'] ?? 0) ?>"
                                        data-ba="<?= (float)($s['monto_real_banco'] ?? 0) ?>"
                                        data-obs="<?= htmlspecialchars($s['observaciones'] ?? '', ENT_QUOTES) ?>"
                                        title="Corregir montos del arqueo">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= APP_URL ?>/caja/reabrir?id=<?= $s['id'] ?>"
                                        class="btn btn-sm btn-outline-success"
                                        title="Reabrir turno para corregir cierre"
                                        onclick="return confirm('¿Seguro que quieres reabrir este turno? Podrás revisarlo y cerrarlo de nuevo.')">
                                        <i class="fas fa-lock-open"></i>
                                    </a>
                                </div>
                            <?php else: ?>
                                <span class="text-muted small">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Editar Arqueo -->
<div class="modal fade" id="modalEditarArqueo" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); color: var(--text-main); border: 1px solid var(--border);">
            <form method="POST" action="<?= APP_URL ?>/caja/editarSesion">
                <input type="hidden" name="sesion_id" id="edit_sesion_id">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-edit me-2" style="color:var(--accent)"></i>Corregir Arqueo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Corrige los montos reales contados. El sistema recalculará las diferencias automáticamente.</p>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Monto Real Efectivo</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="monto_real_efectivo" id="edit_real_ef"
                                class="form-control" min="0" step="1" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Monto Real Banco / Transferencias</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="monto_real_banco" id="edit_real_ba"
                                class="form-control" min="0" step="1" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Observaciones</label>
                        <textarea name="observaciones" id="edit_obs" class="form-control" rows="2"
                            placeholder="Motivo de la corrección..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save me-1"></i> Guardar Corrección
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-editar-arqueo').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('edit_sesion_id').value = this.dataset.id;
            document.getElementById('edit_real_ef').value   = this.dataset.ef;
            document.getElementById('edit_real_ba').value   = this.dataset.ba;
            document.getElementById('edit_obs').value       = this.dataset.obs;
            new bootstrap.Modal(document.getElementById('modalEditarArqueo')).show();
        });
    });
});
</script>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>