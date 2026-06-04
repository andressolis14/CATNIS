<?php
$pageTitle = 'Monitor de Caja';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold"><i class="fas fa-cash-register me-2" style="color:var(--accent)"></i>Turno de Caja Activo
        </h4>
        <p class="text-muted" style="font-size: 14px;">Abierta por: <span
                class="fw-bold"><?= $_SESSION['usuario_nombre'] ?></span> desde las
            <?= date('H:i', strtotime($sesion['fecha_apertura'])) ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <?php if ($_SESSION['rol'] === 'admin'): ?>
            <a href="<?= APP_URL ?>/caja/historial" class="btn btn-outline-dark">
                <i class="fas fa-history me-1"></i> Historial de Arqueos
            </a>
        <?php endif; ?>
        <button class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#modalArqueo">
            <i class="fas fa-lock me-1"></i> CERRAR Y ARQUEAR
        </button>
    </div>
</div>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><i
            class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['exito'])): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['exito']) ?>
    </div>
    <?php unset($_SESSION['exito']); ?>
<?php endif; ?>

<!-- Resumen del Turno (Calculado por Sistema) -->
<div class="row g-4 mb-4">
    <div class="col-md">
        <div class="stat-card" style="border-left: 4px solid #10b981;">
            <div class="stat-details">
                <p class="stat-label">Ventas Turno (Efe)</p>
                <h4 class="stat-value">$<?= number_format($totales['ventas_ef'], 0, ',', '.') ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card" style="border-left: 4px solid #3b82f6;">
            <div class="stat-details">
                <p class="stat-label">Ventas Turno (Trans)</p>
                <h4 class="stat-value">$<?= number_format($totales['ventas_ba'], 0, ',', '.') ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card" style="border-left: 4px solid #f59e0b;">
            <div class="stat-details">
                <p class="stat-label">Abonos Recibidos</p>
                <h4 class="stat-value">
                    $<?= number_format($totales['abonos_ef'] + $totales['abonos_ba'], 0, ',', '.') ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card" style="border-left: 4px solid #ef4444;">
            <div class="stat-details">
                <p class="stat-label">Gastos del Turno</p>
                <h4 class="stat-value">-$<?= number_format($totales['gastos'], 0, ',', '.') ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card" style="border-left: 4px solid #8b5cf6;">
            <div class="stat-details">
                <p class="stat-label">Ventas a Crédito</p>
                <h4 class="stat-value">$<?= number_format($totales['ventas_cr'], 0, ',', '.') ?></h4>
                <p class="text-purple small mb-0 mt-2 fw-bold">Quedaron debiendo:
                    $<?= number_format($totales['saldo_cr'], 0, ',', '.') ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="table-card p-4 text-center bg-success bg-opacity-10 border-success border-opacity-25"
            style="border-radius: 15px;">
            <h6 class="text-uppercase text-success fw-bold small mb-1">Deberías tener en Efectivo</h6>
            <h2 class="fw-bold">$<?= number_format($totales['esperado_ef'], 0, ',', '.') ?></h2>
            <p class="text-muted small mb-0">Base ($<?= number_format($totales['base_ef'], 0) ?>) + Movs
                ($<?= number_format($totales['mov_ef'], 0) ?>) + Ventas - Gastos</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="table-card p-4 text-center bg-primary bg-opacity-10 border-primary border-opacity-25"
            style="border-radius: 15px;">
            <h6 class="text-uppercase text-primary fw-bold small mb-1">Deberías tener en Banco</h6>
            <h2 class="fw-bold">$<?= number_format($totales['esperado_ba'], 0, ',', '.') ?></h2>
            <p class="text-muted small mb-0">Base ($<?= number_format($totales['base_ba'], 0) ?>) + Ventas + Abonos
                Bancarios</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Formulario de Movimiento Extra (Retiros/Ingresos) -->
    <div class="col-lg-4">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Retiro / Ingreso Extra</h6>
            <form action="<?= APP_URL ?>/caja/guardar" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Tipo</label>
                    <div class="d-flex gap-2">
                        <input type="radio" class="btn-check" name="tipo" id="ingreso" value="ingreso" checked>
                        <label class="btn btn-outline-success w-100 btn-sm" for="ingreso">Ingreso</label>
                        <input type="radio" class="btn-check" name="tipo" id="egreso" value="egreso">
                        <label class="btn btn-outline-danger w-100 btn-sm" for="egreso">Retiro</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Monto ($)</label>
                    <input type="number" name="monto" class="form-control" placeholder="0" min="1" step="0.01" required>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold">Concepto</label>
                    <input type="text" name="descripcion" class="form-control" placeholder="Ej: Pago a dueño" required>
                </div>
                <button type="submit" class="btn btn-primary-custom btn-sm w-100">Registrar Movimiento</button>
            </form>
        </div>
    </div>

    <!-- Historial de Turno Actual -->
    <div class="col-lg-8">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Movimientos del Turno</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Concepto</th>
                            <th class="text-end">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movimientos as $m): ?>
                            <tr>
                                <td class="text-muted"><?= date('H:i', strtotime($m['fecha'])) ?></td>
                                <td>
                                    <?php
                                    $badge = 'bg-secondary';
                                    $color = 'secondary';
                                    if ($m['tipo'] === 'venta') {
                                        $badge = 'bg-primary';
                                        $color = 'primary';
                                    } elseif ($m['tipo'] === 'egreso' || $m['tipo'] === 'gasto') {
                                        $badge = 'bg-danger';
                                        $color = 'danger';
                                    } elseif ($m['tipo'] === 'ingreso' || $m['tipo'] === 'abono') {
                                        $badge = 'bg-success';
                                        $color = 'success';
                                    }
                                    ?>
                                    <span class="badge <?= $badge ?> bg-opacity-10 text-<?= $color ?> px-2 py-1">
                                        <?= strtoupper($m['tipo']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($m['descripcion']) ?></td>
                                <td class="text-end fw-bold">
                                    <span
                                        class="text-<?= ($m['tipo'] === 'egreso' || $m['tipo'] === 'gasto') ? 'danger' : 'success' ?>">
                                        <?= ($m['tipo'] === 'egreso' || $m['tipo'] === 'gasto') ? '-' : '+' ?>$<?= number_format($m['monto'], 0, ',', '.') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($movimientos)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No hay movimientos financieros en este
                                    turno.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE ARQUEO / CIERRE -->
<div class="modal fade" id="modalArqueo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-calculator me-2"></i>Arqueo de Caja (Cierre)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= APP_URL ?>/caja/cerrar" method="POST">
                <input type="hidden" name="sesion_id" value="<?= $sesion['id'] ?>">
                <div class="modal-body p-4">
                    <p class="text-muted small">Cuenta el dinero real que tienes físicamente e ingresalo aquí.</p>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Efectivo Real Contado ($)</label>
                        <input type="number" name="monto_real_efectivo"
                            class="form-control form-control-lg border-danger border-opacity-25"
                            placeholder="Cuenta tus billetes" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Saldo Real en Banco ($)</label>
                        <input type="number" name="monto_real_banco"
                            class="form-control form-control-lg border-primary border-opacity-25"
                            value="<?= $totales['esperado_ba'] ?>" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Observaciones de Cierre</label>
                        <textarea name="observaciones" class="form-control" rows="2"
                            placeholder="Opcional..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-link text-muted text-decoration-none"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger px-4 py-2 fw-bold">
                        GUARDAR Y CERRAR CAJA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>