<?php
// ============================================================
// SCRIPT TEMPORAL: Borrar ventas de prueba
// ELIMINAR ESTE ARCHIVO después de usarlo
// ============================================================
define('APP_ROOT', dirname(__DIR__));
define('DB_HOST', 'localhost');
define('DB_USER', 'catnisba_CATNISBAKERY');
define('DB_PASS', 'C4tn1sH0s92*/');
define('DB_NAME', 'catnisba_tuusuario_catnis');
define('DB_CHARSET', 'utf8mb4');

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
$db  = new PDO($dsn, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$mensaje = '';
$error   = '';

// --- Acción: BORRAR ventas seleccionadas ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ids'])) {
    $ids = array_filter(array_map('intval', $_POST['ids']));

    if (!empty($ids)) {
        $placeholders = implode(',', $ids);
        try {
            $db->beginTransaction();

            // 1. Obtener deudas relacionadas a esas ventas
            $deudas = $db->query("SELECT id FROM deudas WHERE venta_id IN ($placeholders)")->fetchAll();
            $deuda_ids = array_column($deudas, 'id');

            // 2. Borrar abonos de esas deudas
            if (!empty($deuda_ids)) {
                $dp = implode(',', $deuda_ids);
                $db->exec("DELETE FROM abonos WHERE deuda_id IN ($dp)");
            }

            // 3. Borrar deudas
            $db->exec("DELETE FROM deudas WHERE venta_id IN ($placeholders)");

            // 4. Borrar detalle_ventas
            $db->exec("DELETE FROM detalle_ventas WHERE venta_id IN ($placeholders)");

            // 5. Borrar ventas
            $db->exec("DELETE FROM ventas WHERE id IN ($placeholders)");

            $db->commit();
            $mensaje = "✅ Eliminadas " . count($ids) . " venta(s) y todos sus registros relacionados.";
        } catch (Exception $e) {
            $db->rollBack();
            $error = "❌ Error: " . $e->getMessage();
        }
    }
}

// --- Cargar ventas recientes para mostrar ---
$ventas = $db->query("
    SELECT v.id, v.fecha, v.tipo, v.metodo_pago, v.total, v.notas,
           u.nombre as vendedor,
           c.nombre as cliente,
           (SELECT COUNT(*) FROM deudas WHERE venta_id = v.id) as tiene_deuda,
           (SELECT COALESCE(SUM(monto),0) FROM abonos a JOIN deudas d ON d.id = a.deuda_id WHERE d.venta_id = v.id) as total_abonado
    FROM ventas v
    JOIN usuarios u ON u.id = v.usuario_id
    LEFT JOIN clientes c ON c.id = v.cliente_id
    ORDER BY v.fecha DESC
    LIMIT 50
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Borrar Ventas de Prueba</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; padding: 30px; }
.card { border-radius: 12px; }
thead th { background: #212529; color: white; }
.badge-credito { background: #7c3aed; color: white; }
.badge-contado { background: #059669; color: white; }
</style>
</head>
<body>
<div class="container-fluid" style="max-width:1100px">
    <div class="card shadow p-4 mb-4">
        <h4 class="fw-bold mb-1">🗑️ Borrar Ventas de Prueba</h4>
        <p class="text-muted small mb-0">Selecciona las ventas que quieres eliminar. Se borrarán también sus detalles, deudas y abonos.</p>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-success fw-bold"><?= $mensaje ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger fw-bold"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="card shadow">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:40px">
                                <input type="checkbox" id="selectAll" title="Seleccionar todas">
                            </th>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Método</th>
                            <th>Cliente</th>
                            <th>Notas</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Abonado</th>
                            <th>Registrado por</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="<?= $v['id'] ?>" class="row-check">
                            </td>
                            <td class="fw-bold text-muted">#<?= $v['id'] ?></td>
                            <td class="small"><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                            <td>
                                <span class="badge badge-<?= $v['tipo'] ?> px-2 py-1">
                                    <?= strtoupper($v['tipo']) ?>
                                </span>
                            </td>
                            <td class="small"><?= htmlspecialchars($v['metodo_pago'] ?? '-') ?></td>
                            <td class="small"><?= htmlspecialchars($v['cliente'] ?? '-') ?></td>
                            <td class="small text-muted"><?= htmlspecialchars($v['notas'] ?? '') ?></td>
                            <td class="text-end fw-bold">$<?= number_format($v['total'], 0, ',', '.') ?></td>
                            <td class="text-end small <?= $v['total_abonado'] > 0 ? 'text-success' : 'text-muted' ?>">
                                <?= $v['total_abonado'] > 0 ? '$' . number_format($v['total_abonado'], 0, ',', '.') : '-' ?>
                            </td>
                            <td class="small"><?= htmlspecialchars($v['vendedor']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-danger px-4 fw-bold"
                onclick="return confirm('¿Seguro? Esto eliminará las ventas seleccionadas y todos sus registros (deudas, abonos, detalles). No se puede deshacer.')">
                🗑️ ELIMINAR SELECCIONADAS
            </button>
            <a href="/" class="btn btn-outline-secondary">Volver al sistema</a>
        </div>
    </form>

    <div class="alert alert-warning mt-4 small">
        ⚠️ <strong>Recuerda:</strong> Elimina este archivo (<code>public/borrar_pruebas.php</code>) del servidor cuando termines.
    </div>
</div>

<script>
document.getElementById('selectAll').addEventListener('change', function () {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
});
</script>
</body>
</html>
