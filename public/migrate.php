<?php
require_once __DIR__ . '/../config/db.php';
try {
    $db = getDB();
    $db->exec("ALTER TABLE sesiones_caja ADD COLUMN monto_ventas_credito DECIMAL(10,2) DEFAULT 0 AFTER monto_esperado_banco");
    echo "COLUMNA AGREGADA EXITOSAMENTE";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
unlink(__FILE__); // Autodestrucción
?>