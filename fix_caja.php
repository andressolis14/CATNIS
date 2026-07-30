<?php
require_once __DIR__ . '/config/db.php';

try {
    $db = getDB();
    $stmt = $db->prepare("UPDATE sesiones_caja SET monto_inicial_efectivo = 197250 WHERE estado = 'abierta'");
    $stmt->execute();
    echo "¡Listo! Se actualizaron " . $stmt->rowCount() . " sesiones abiertas. El monto inicial en efectivo ahora es 197,250.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
