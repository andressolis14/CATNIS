<?php
// ============================================================
// SCRIPT DE LIMPIEZA DE BASE DE DATOS
// ============================================================
// Este script vacía todas las tablas excepto usuarios y productos.

require_once __DIR__ . '/config/db.php';

try {
    $db = getDB();

    // 1. Desactivar validación de llaves foráneas temporalmente
    // Esto es necesario para poder vaciar tablas que están conectadas (ej. ventas y ventas_detalles)
    $db->exec('SET FOREIGN_KEY_CHECKS = 0');

    // 2. Obtener la lista de todas las tablas en la base de datos
    $stmt = $db->query('SHOW TABLES');
    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 3. Definir las tablas que NO QUEREMOS BORRAR
    // Si quieres conservar a los clientes, añade 'clientes' a esta lista.
    $excluidas = ['usuarios', 'productos'];

    $tablasBorradas = [];

    // 4. Ejecutar la limpieza general (TRUNCATE vacía y reinicia los IDs)
    foreach ($tablas as $tabla) {
        if (!in_array($tabla, $excluidas)) {
            $db->exec("TRUNCATE TABLE `$tabla`");
            $tablasBorradas[] = $tabla;
        }
    }

    // 5. Volver a activar la validación de llaves foráneas por seguridad
    $db->exec('SET FOREIGN_KEY_CHECKS = 1');

    // Mostrar el resultado final
    echo "<div style='font-family: Arial; max-width: 600px; margin: 40px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);'>";
    echo "<h2 style='color: #10b981;'>✅ Limpieza Completada con Éxito</h2>";
    echo "<p>El sistema está listo para empezar de cero.</p>";

    echo "<h3>Tablas protegidas (Intactas):</h3>";
    echo "<ul><li style='color: #3b82f6;'><b>" . implode("</b></li><li style='color: #3b82f6;'><b>", $excluidas) . "</b></li></ul>";

    echo "<h3>Tablas vaciadas y reiniciadas a 0:</h3><ul>";
    foreach ($tablasBorradas as $tb) {
        echo "<li>$tb</li>";
    }
    echo "</ul>";

    echo "<hr>";
    echo "<p style='color: #ef4444; font-weight: bold;'>⚠️ ¡PELIGRO! Por favor elimina (o desactiva) este script ahora que has terminado, para evitar borrar tus datos de producción por accidente.</p>";
    echo "</div>";

} catch (Exception $e) {
    if (isset($db)) {
        $db->exec('SET FOREIGN_KEY_CHECKS = 1'); // Asegurar reactivación en caso de fallo
    }
    echo "<h2 style='color: red;'>❌ Error durante la limpieza:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
