<?php
$pageTitle = 'Clientes';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h4><i class="fas fa-users me-2" style="color:var(--accent)"></i>Clientes</h4>
        <p>Gestión de clientes del negocio</p>
    </div>
    <a href="<?= APP_URL ?>/clientes/crear" class="btn-primary-custom">
        <i class="fas fa-plus me-2"></i>Nuevo Cliente
    </a>
</div>

<div class="table-card">
    <?php if (empty($clientes)): ?>
        <div class="p-5 text-center">
            <i class="fas fa-users-slash fa-3x mb-3" style="color:var(--text-dim)"></i>
            <h6 style="color:var(--text-muted)">Sin clientes registrados</h6>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead><tr>
                    <th>#</th><th>Nombre</th><th>Teléfono</th><th>Correo</th><th class="text-end">Acciones</th>
                </tr></thead>
                <tbody>
                <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td class="text-dim"><?= $c['id'] ?></td>
                        <td class="fw-600 text-main"><?= htmlspecialchars($c['nombre']) ?></td>
                        <td class="text-muted"><?= htmlspecialchars($c['telefono'] ?: '—') ?></td>
                        <td class="text-muted"><?= htmlspecialchars($c['correo'] ?: '—') ?></td>
                        <td class="text-end">
                            <a href="<?= APP_URL ?>/clientes/detalle?id=<?= $c['id'] ?>" class="btn-sm-icon me-1" style="background:rgba(139,92,246,0.15);color:var(--accent-purple);" title="Ver historial">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= APP_URL ?>/clientes/editar?id=<?= $c['id'] ?>" class="btn-sm-icon me-1" style="background:rgba(59,130,246,0.15);color:var(--accent-blue);" title="Editar">
                                <i class="fas fa-pen"></i>
                            </a>
                            <a href="<?= APP_URL ?>/clientes/eliminar?id=<?= $c['id'] ?>" class="btn-sm-icon" style="background:rgba(239,68,68,0.15);color:var(--accent-red);" title="Eliminar"
                               onclick="return confirm('¿Eliminar este cliente?')">
                                <i class="fas fa-trash"></i>
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
