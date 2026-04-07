<?php
if (!defined('APP_NAME')) {
    require_once dirname(__DIR__, 2) . '/config/config.php';
    require_once dirname(__DIR__, 2) . '/config/db.php';
    if (!isset($_SESSION)) session_start();
}
if (isset($_SESSION['usuario_id'])) {
    header('Location: ' . APP_URL . '/dashboard'); exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= APP_URL ?>/../public/css/app.css" rel="stylesheet">
</head>
<body>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo"><i class="fas fa-bread-slice"></i></div>
        <h4 class="text-center fw-bold mb-1">Crear Cuenta</h4>
        <p class="text-center mb-4" style="color:var(--text-muted);font-size:13px;"><?= APP_NAME ?></p>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert py-2 mb-3" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;border-radius:8px;font-size:13px;">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="<?= APP_URL ?>/auth/proceso_registro" method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre completo</label>
                <input type="text" name="nombre" class="form-control" placeholder="Tu nombre" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="correo" class="form-control" placeholder="correo@ejemplo.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="contrasena" class="form-control" placeholder="Mínimo 6 caracteres" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Confirmar contraseña</label>
                <input type="password" name="confirmar" class="form-control" placeholder="Repite tu contraseña" required>
            </div>
            <button type="submit" class="btn-primary-custom w-100 mb-3">
                <i class="fas fa-user-plus me-2"></i>Crear Cuenta
            </button>
        </form>
        <p class="text-center mb-0" style="font-size:13px;color:var(--text-muted);">
            ¿Ya tienes cuenta? <a href="<?= APP_URL ?>/auth/login" style="color:var(--accent);text-decoration:none;">Iniciar sesión</a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
