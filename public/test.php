<?php
require_once __DIR__ . '/../config/db.php';
$db = getDB();
header('Content-Type: text/plain');
echo "--- BUSCANDO DUPLICADOS EN DEUDAS ---\n";
try {
    $res = $db->query("
        SELECT venta_id, COUNT(*) as cantidad, GROUP_CONCAT(id) as ids 
        FROM deudas 
        GROUP BY venta_id 
        HAVING cantidad > 1
    ")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($res)) {
        echo "No hay duplicados por venta_id.\n";
    } else {
        foreach ($res as $row) {
            echo "Venta ID: {$row['venta_id']} tiene {$row['cantidad']} deudas (IDs: {$row['ids']})\n";
        }
    }

    echo "\n--- ESTRUCTURA DE LA TABLA DEUDAS ---\n";
    $cols = $db->query("DESCRIBE deudas")->fetchAll(PDO::FETCH_ASSOC);
    print_r($cols);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>