<?php
$pageTitle = 'Movimientos de Insumos';
require_once APP_ROOT . '/views/layout/header.php';
$badgeMap = [
    'entrada' => ['bg' => 'rgba(16,185,129,.15)',  'color' => '#10b981', 'icon' => 'fa-arrow-down',  'label' => 'Entrada'],
    'salida'  => ['bg' => 'rgba(245,158,11,.15)',  'color' => '#f59e0b', 'icon' => 'fa-arrow-up',    'label' => 'Salida'],
    'merma'   => ['bg' => 'rgba(239,68,68,.15)',   'color' => '#ef4444', 'icon' => 'fa-trash',       'label' => 'Merma'],
    'ajuste'  => ['bg' => 'rgba(59,130,246,.15)',  'color' => '#3b82f6', 'icon' => 'fa-sliders',     'label' => 'Ajuste'],
];
?>

<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold"><i class="fas fa-arrows-up-down me-2" style="color:var(--accent)"></i>Movimientos de Insumos</h4>
        <p style="font-size:13px;color:var(--text-muted);">Entradas, salidas, mermas y ajustes de inventario.</p>
    </div>
    <a href="<?= APP_URL ?>/movimientos/crear" class="btn-primary-custom">
        <i class="fas fa-plus me-2"></i>Nuevo Movimiento
    </a>
</div>

<!-- Filtros -->
<form method="GET" action="<?= APP_URL ?>/movimientos" class="mb-4">
    <div class="table-card p-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label" style="font-size:12px;">Insumo</label>
                <select name="insumo_id" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <?php foreach ($insumos as $ins): ?>
                        <option value="<?= $ins['id'] ?>" <?= ($_GET['insumo_id'] ?? '') == $ins['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ins['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:12px;">Tipo</label>
                <select name="tipo" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="entrada" <?= ($_GET['tipo'] ?? '') === 'entrada' ? 'selected' : '' ?>>Entrada</option>
                    <option value="salida"  <?= ($_GET['tipo'] ?? '') === 'salida'  ? 'selected' : '' ?>>Salida</option>
                    <option value="merma"   <?= ($_GET['tipo'] ?? '') === 'merma'   ? 'selected' : '' ?>>Merma</option>
                    <option value="ajuste"  <?= ($_GET['tipo'] ?? '') === 'ajuste'  ? 'selected' : '' ?>>Ajuste</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:12px;">Desde</label>
                <input type="date" name="fecha_inicio" class="form-control form-control-sm" value="<?= $_GET['fecha_inicio'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:12px;">Hasta</label>
                <input type="date" name="fecha_fin" class="form-control form-control-sm" value="<?= $_GET['fecha_fin'] ?? '' ?>">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-primary-custom flex-fill" style="padding:7px;">
                    <i class="fas fa-filter me-1"></i>Filtrar
                </button>
                <a href="<?= APP_URL ?>/movimientos" class="btn flex-fill" style="background:var(--bg-card2);border:1px solid var(--border);color:var(--text-muted);padding:7px;border-radius:8px;text-align:center;font-size:13px;text-decoration:none;">
                    <i class="fas fa-xmark me-1"></i>Limpiar
                </a>
            </div>
        </div>
    </div>
</form>

<div class="table-card">
    <?php if (empty($movimientos)): ?>
        <div class="p-5 text-center">
            <i class="fas fa-arrows-up-down fa-3x mb-3" style="color:var(--text-dim)"></i>
            <h6 style="color:var(--text-muted)">Sin movimientos registrados</h6>
            <a href="<?= APP_URL ?>/movimientos/crear" class="btn-primary-custom mt-3 d-inline-block">
                Registrar primero
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Insumo</th>
                        <th class="text-center">Cantidad</th>
                        <th>Motivo</th>
                        <th>Registrado por</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movimientos as $m):
                        $b = $badgeMap[$m['tipo']] ?? $badgeMap['ajuste'];
                        $esEntrada = in_array($m['tipo'], ['entrada', 'ajuste']);
                    ?>
                    <tr>
                        <td style="color:var(--text-muted);font-size:13px;">
                            <?= date('d/m/Y', strtotime($m['fecha'])) ?>
                        </td>
                        <td>
                            <span style="background:<?= $b['bg'] ?>;color:<?= $b['color'] ?>;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                                <i class="fas <?= $b['icon'] ?> me-1"></i><?= $b['label'] ?>
                            </span>
                        </td>
                        <td class="fw-600"><?= htmlspecialchars($m['insumo_nombre']) ?></td>
                        <td class="text-center fw-bold" style="color:<?= $esEntrada ? '#10b981' : '#ef4444' ?>;">
                            <?= $esEntrada ? '+' : '-' ?><?= number_format((float)$m['cantidad'], 0, ',', '.') ?>
                            <span style="font-size:11px;font-weight:400;color:var(--text-muted);"> <?= $m['unidad_medida'] ?></span>
                        </td>
                        <td style="font-size:13px;color:var(--text-muted);">
                            <?= $m['descripcion'] ? htmlspecialchars($m['descripcion']) : '—' ?>
                        </td>
                        <td style="font-size:12px;color:var(--text-dim);"><?= htmlspecialchars($m['usuario_nombre']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
