<div class="table-card">
    <div class="table-card-header"><h6><i class="fas fa-list-check me-2"></i>Abonos Registrados</h6></div>
    <?php if (empty($abonos)): ?>
        <div class="p-3 text-center" style="color:var(--text-dim);font-size:13px;">Sin abonos aún.</div>
    <?php else: ?>
        <table class="table">
            <thead><tr><th>Fecha</th><th>Método</th><th class="text-end">Monto</th><th>Nota</th><th></th></tr></thead>
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
                    <td class="text-end" style="white-space:nowrap;">
                        <button class="btn-sm-icon me-1"
                            style="background:rgba(59,130,246,0.12);color:#3b82f6;"
                            title="Editar abono"
                            onclick="abrirEditarAbono(<?= $a['id'] ?>, <?= $deuda['id'] ?>, <?= $a['monto'] ?>, '<?= $a['metodo_pago'] ?>', '<?= htmlspecialchars(addslashes($a['nota'] ?? ''), ENT_QUOTES) ?>')">
                            <i class="fas fa-pen"></i>
                        </button>
                        <a href="<?= APP_URL ?>/deudas/eliminarAbono?abono_id=<?= $a['id'] ?>&deuda_id=<?= $deuda['id'] ?>"
                            class="btn-sm-icon"
                            style="background:rgba(239,68,68,0.12);color:#ef4444;"
                            title="Eliminar abono"
                            onclick="return confirm('¿Eliminar este abono de $<?= number_format($a['monto'], 0, ',', '.') ?>? El saldo se recalculará.')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
