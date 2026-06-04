<?php
require_once __DIR__ . '/../config/db.php';
$db = getDB();
header('Content-Type: text/plain');
echo "--- BUSCANDO DUPLICADOS EN DEUDAS ---\n";
try {
    // 1. Verificar si hay deudas con el mismo venta_id
    $res = $db->query("
        SELECT venta_id, COUNT(*) as cantidad, GROUP_CONCAT(id) as ids 
        FROM deudas 
        GROUP BY venta_id 
        HAVING cantidad > 1
    ")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($res)) {
        echo "No hay deudas con el mismo venta_id.\n";
    } else {
        echo "ENCONTRADOS DUPLICADOS POR VENTA_ID:\n";
        print_r($res);
    }

    // 2. Verificar si hay deudas con la misma fecha y monto para el mismo cliente (posible doble click)
    $res2 = $db->query("
        SELECT cliente_id, total, fecha, COUNT(*) as cantidad, GROUP_CONCAT(id) as ids
        FROM deudas
        GROUP BY cliente_id, total, fecha
        HAVING cantidad > 1
    ")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($res2)) {
        echo "No hay deudas idénticas (cliente, monto, fecha).\n";
    } else {
        echo "ENCONTRADAS DEUDAS IDÉNTICAS (Posible doble registro):\n";
        print_r($res2);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>