<?php
// ============================================================
// MIGRACIÓN: Tablas para Catálogo Web
// Ejecutar una sola vez y luego eliminar
// ============================================================
define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/config/config.php';
require_once APP_ROOT . '/config/db.php';

$db = getDB();
$errores = [];
$ok = [];

// Tabla de categorías
try {
    $db->exec("
        CREATE TABLE IF NOT EXISTS catalogo_categorias (
            id     INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            icono  VARCHAR(20)  DEFAULT '🐾',
            orden  INT          DEFAULT 0,
            activo TINYINT(1)   DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    $ok[] = "Tabla <code>catalogo_categorias</code> creada.";
} catch (Exception $e) {
    $errores[] = "catalogo_categorias: " . $e->getMessage();
}

// Tabla de productos del catálogo web
try {
    $db->exec("
        CREATE TABLE IF NOT EXISTS catalogo_productos (
            id           INT AUTO_INCREMENT PRIMARY KEY,
            categoria_id INT NULL,
            nombre       VARCHAR(200)   NOT NULL,
            descripcion  TEXT           NULL,
            precio       DECIMAL(10,2)  NOT NULL DEFAULT 0,
            imagen       VARCHAR(300)   NULL,
            activo       TINYINT(1)     DEFAULT 1,
            orden        INT            DEFAULT 0,
            created_at   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (categoria_id) REFERENCES catalogo_categorias(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    $ok[] = "Tabla <code>catalogo_productos</code> creada.";
} catch (Exception $e) {
    $errores[] = "catalogo_productos: " . $e->getMessage();
}

// Categorías por defecto (solo si la tabla está vacía)
try {
    $count = $db->query("SELECT COUNT(*) FROM catalogo_categorias")->fetchColumn();
    if ($count == 0) {
        $cats = [
            ['Pasteles',  '🎂', 1],
            ['Galletas',  '🍪', 2],
            ['Especiales','⭐', 3],
            ['Tortas',    '🎁', 4],
            ['Combos',    '🎀', 5],
        ];
        $stmt = $db->prepare("INSERT INTO catalogo_categorias (nombre, icono, orden) VALUES (?, ?, ?)");
        foreach ($cats as $c) $stmt->execute($c);
        $ok[] = "Categorías por defecto insertadas (Pasteles, Galletas, Especiales, Tortas, Combos).";
    } else {
        $ok[] = "Categorías ya existentes, no se insertaron datos por defecto.";
    }
} catch (Exception $e) {
    $errores[] = "Insertar categorías: " . $e->getMessage();
}

// Crear carpeta de imágenes
$dir = APP_ROOT . '/public/img/catalogo/';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
    $ok[] = "Carpeta <code>public/img/catalogo/</code> creada.";
} else {
    $ok[] = "Carpeta <code>public/img/catalogo/</code> ya existe.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Migración Catálogo Web</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5" style="background:#f8f9fa">
<div class="container" style="max-width:700px">
    <h4 class="mb-4 fw-bold">Migración: Catálogo Web</h4>
    <?php foreach ($ok as $msg): ?>
        <div class="alert alert-success py-2">✅ <?= $msg ?></div>
    <?php endforeach; ?>
    <?php foreach ($errores as $e): ?>
        <div class="alert alert-danger py-2">❌ <?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>
    <?php if (empty($errores)): ?>
        <div class="alert alert-info mt-3">
            <strong>¡Todo listo!</strong> Ahora puedes eliminar este archivo del servidor.<br>
            <a href="<?= APP_URL ?>/catalogoweb" class="btn btn-dark btn-sm mt-2">Ir al Catálogo Web →</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
