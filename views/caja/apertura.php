<?php
$pageTitle = 'Apertura de Caja';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-5">
        <div class="table-card p-5 text-center shadow-lg" style="border-top: 5px solid var(--success);">
            <div class="mb-4">
                <div class="stat-icon mx-auto mb-3"
                    style="background: rgba(16,185,129,0.1); color: #10b981; width: 70px; height: 70px; line-height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-lock fa-2x"></i>
                </div>
                <h3 class="fw-bold">Caja Cerrada</h3>
                <p class="text-muted">Para empezar a registrar ventas y movimientos, primero debes abrir el turno del
                    día.</p>
            </div>

            <form action="<?= APP_URL ?>/caja/abrir" method="POST" class="text-start">
                <div class="mb-3">
                    <label class="form-label fw-bold">Base Inicial en Efectivo (Billetes/Monedas)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="fas fa-money-bill-wave text-success"></i></span>
                        <input type="number" name="monto_inicial_efectivo" class="form-control border-start-0"
                            placeholder="0" min="0" step="0.01" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Base en Banco / Transferencia</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="fas fa-university text-blue"></i></span>
                        <input type="number" name="monto_inicial_banco" class="form-control border-start-0"
                            placeholder="0" min="0" step="0.01" required>
                    </div>
                    <small class="text-muted">Ingresa el saldo actual de la cuenta bancaria si aplica.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Observaciones (Opcional)</label>
                    <textarea name="observaciones" class="form-control" rows="2"
                        placeholder="Ej: Recibí caja con muchos billetes de 2.000..."></textarea>
                </div>

                <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-sm">
                    <i class="fas fa-key me-2"></i>ABRIR TURNO AHORA
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>