<?php
require_once __DIR__ . '/../config/db.php';
try {
    $db = getDB();
    $db->exec("ALTER TABLE ventas MODIFY COLUMN estado VARCHAR(20) DEFAULT 'completada'");
    echo "✅ Listo. El campo 'estado' ahora acepta el valor 'anulada'. Puedes cerrar esta página.";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
unlink(__FILE__);
?>
