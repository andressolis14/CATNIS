<?php
// ============================================================
// VISTA: Reporte de Compras por Ítem
// ============================================================
$pageTitle = 'Reporte de Ítems Comprados';
require_once APP_ROOT . '/views/layout/header.php';

$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];

$fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
$fecha_fin    = $_GET['fecha_fin']    ?? date('Y-m-d');

// Obtener los datos (esto debería ir en el controlador, pero para prototipo rápido en vista)
$db = getDB();
$sql = "SELECT m.codigo, m.nombre, SUM(d.cantidad) as total_cantidad, SUM(d.monto * d.cantidad) as total_gastado
        FROM detalle_gastos d
        JOIN gastos g ON d.gasto_id = g.id
        JOIN items_maestro_gastos m ON d.item_maestro_id = m.id
        WHERE g.fecha BETWEEN :inicio AND :fin
        GROUP BY m.id
        ORDER BY total_gastado DESC";
$stmt = $db->prepare($sql);
$stmt->execute([':inicio' => $fecha_inicio, ':fin' => $fecha_fin . ' 23:59:59']);
$reporte = $stmt->fetchAll();
?>

<div class="page-header">
    <h4><i class="fas fa-boxes me-2" style="color:var(--accent)"></i>Reporte de Adquisiciones</h4>
</div>

<div class="table-card p-4 mb-4">
    <form method="GET" action="<?= APP_URL ?>/reportes/items" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label text-muted">Desde</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?= $fecha_inicio ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label text-muted">Hasta</label>
            <input type="date" name="fecha_fin" class="form-control" value="<?= $fecha_fin ?>">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn-primary-custom flex-grow-1">Filtrar</button>
            <a href="<?= APP_URL ?>/reportes/items" class="btn" style="background:var(--bg-card2); border:1px solid var(--border); color:var(--text-muted);" title="Restablecer">
                <i class="fas fa-undo"></i>
            </a>
            <a href="<?= APP_URL ?>/reportes" class="btn" style="background:var(--bg-card2); border:1px solid var(--border); color:var(--text-muted);" title="Volver">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-dark-custom">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Ítem</th>
                    <th class="text-center">Cant. Comprada</th>
                    <th class="text-end">Inversión Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($reporte)): ?>
                    <tr><td colspan="4" class="text-center py-4 text-muted">No hay registros para este periodo.</td></tr>
                <?php else: ?>
                    <?php foreach($reporte as $r): ?>
                        <tr>
                            <td class="text-accent fw-600"><?= $r['codigo'] ?></td>
                            <td><?= htmlspecialchars($r['nombre']) ?></td>
                            <td class="text-center text-main"><?= number_format($r['total_cantidad'], 0) ?></td>
                            <td class="text-end text-success fw-600">$<?= number_format($r['total_gastado'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
