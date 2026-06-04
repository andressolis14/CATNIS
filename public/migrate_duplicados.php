<?php
require_once __DIR__ . '/../config/db.php';
$db = getDB();

echo "<h3>Diagnóstico de Duplicados Catnis</h3>";

try {
    // 1. Mostrar qué hay en la tabla deudas
    $stmt = $db->query("SELECT id, venta_id, cliente_id, total, saldo FROM deudas ORDER BY id DESC LIMIT 20");
    $rows = $stmt->fetchAll();
    echo "<b>Últimos 20 registros en 'deudas':</b><br><pre>";
    print_r($rows);
    echo "</pre>";

    // 2. Buscar duplicados por venta_id
    $stmt = $db->query("SELECT venta_id, COUNT(*) as c, GROUP_CONCAT(id) as ids FROM deudas GROUP BY venta_id HAVING c > 1");
    $dups = $stmt->fetchAll();

    if (empty($dups)) {
        echo "<p style='color:orange'>No encontré registros con el mismo venta_id repetido.</p>";
    } else {
        echo "<p style='color:red'>Encontré los siguientes duplicados de venta_id:</p><ul>";
        foreach ($dups as $d) {
            echo "<li>Venta #{$d['venta_id']} está repetida en Deudas IDs: {$d['ids']}</li>";
            $idsArr = explode(',', $d['ids']);
            $keeping = $idsArr[0];
            $deleting = array_slice($idsArr, 1);

            foreach ($deleting as $delId) {
                // Mover abonos si los hay
                $db->prepare("UPDATE abonos SET deuda_id = ? WHERE deuda_id = ?")->execute([$keeping, $delId]);
                // Borrar
                $db->prepare("DELETE FROM deudas WHERE id = ?")->execute([$delId]);
                echo " - Borrado registro ID: $delId (Abonos movidos al ID: $keeping)<br>";
            }
        }
        echo "</ul>";
    }

    // 3. Agregar el índice UNIQUE para que no vuelva a pasar
    try {
        $db->exec("ALTER TABLE deudas ADD UNIQUE INDEX `idx_unique_venta_v3` (venta_id)");
        echo "<p style='color:green'><b>¡ÉXITO!</b> Se agregó el candado de seguridad UNIQUE en venta_id.</p>";
    } catch (Exception $e) {
        echo "<p style='color:gray'>El índice UNIQUE no se pudo agregar (posiblemente ya existe): " . $e->getMessage() . "</p>";
    }

    echo "<hr><a href='deudas'>Volver a Cuentas por Cobrar</a>";

} catch (Exception $e) {
    echo "<p style='color:red'>ERROR: " . $e->getMessage() . "</p>";
}
?>