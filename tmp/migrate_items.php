<?php
require_once __DIR__ . '/../config/db.php';
$db = getDB();
try {
    $db->exec("ALTER TABLE items_maestro_gastos ADD COLUMN unidad_medida VARCHAR(50) DEFAULT 'unid' AFTER nombre");
    echo "Migración exitosa: columna unidad_medida añadida.";
} catch (Exception $e) {
    echo "Error o ya existía: " . $e->getMessage();
}
unlink(__FILE__); // Autodestrucción
