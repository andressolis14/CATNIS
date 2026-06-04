<?php
$pageTitle = 'Historial: ' . htmlspecialchars($cliente['nombre']);
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header">
    <h4><i class="fas fa-user me-2" style="color:var(--accent)"></i><?= htmlspecialchars($cliente['nombre']) ?></h4>
    <p><a href="<?= APP_URL ?>/clientes" style="color:var(--accent);text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Volver a clientes</a></p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card"><div class="stat-icon icon-blue"><i class="fas fa-phone"></i></div>
        <div class="stat-label">Teléfono</div><div style="font-size:16px;font-weight:600;margin-top:4px;"><?= htmlspecialchars($cliente['telefono'] ?: 'No registrado') ?></div></div>
    </div>
    <div class="col-md-4">
        <div class="stat-card"><div class="stat-icon icon-green"><i class="fas fa-envelope"></i></div>
        <div class="stat-label">Correo</div><div style="font-size:15px;font-weight:600;margin-top:4px;"><?= htmlspecialchars($cliente['correo'] ?: 'No registrado') ?></div></div>
    </div>
    <div class="col-md-4">
        <div class="stat-card"><div class="stat-icon icon-amber"><i class="fas fa-shopping-bag"></i></div>
        <div class="stat-label">Total Compras</div><div class="stat-value text-amber"><?= count($historial) ?></div></div>
    </div>
    
    <!-- Ficha de la Mascota -->
    <?php if (!empty($cliente['nombre_mascota'])): ?>
    <div class="col-md-12 mt-3">
        <div class="form-card" style="border-left: 4px solid var(--accent);">
            <div class="d-flex align-items-center">
                <div class="me-4" style="font-size: 30px; color: var(--accent);">
                    <i class="fas fa-dog"></i>
                </div>
                <div>
                    <h5 class="mb-1" style="color: var(--accent);">Ficha de Mascota: <?= htmlspecialchars($cliente['nombre_mascota']) ?></h5>
                    <?php if (!empty($cliente['cumpleanos_mascota'])): ?>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-cake-candles me-2 text-pink"></i>
                            Cumpleaños: <?= date('d/m/Y', strtotime($cliente['cumpleanos_mascota'])) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="table-card">
    <div class="table-card-header"><h6><i class="fas fa-history me-2" style="color:var(--accent)"></i>Historial de Ventas</h6></div>
    <?php if (empty($historial)): ?>
        <div class="p-4 text-center" style="color:var(--text-dim)">Sin compras registradas.</div>
    <?php else: ?>
        <table class="table">
            <thead><tr><th>Fecha</th><th>Tipo</th><th>Productos</th><th class="text-end">Total</th><th>Estado</th></tr></thead>
            <tbody>
            <?php foreach ($historial as $v): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                    <td><span class="badge-<?= $v['tipo'] ?>"><?= ucfirst($v['tipo']) ?></span></td>
                    <td><?= $v['num_productos'] ?> item(s)</td>
                    <td class="text-end fw-bold text-green">$<?= number_format($v['total'], 2) ?></td>
                    <td><span class="badge-<?= $v['estado'] ?>"><?= ucfirst($v['estado']) ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
