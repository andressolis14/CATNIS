<?php
// Guard: si las constantes no están cargadas, bootstrapear desde el router
if (!defined('APP_NAME')) {
    // Cargar config directamente si se accede sin pasar por index.php
    require_once dirname(__DIR__, 2) . '/config/config.php';
    require_once dirname(__DIR__, 2) . '/config/db.php';
    if (!isset($_SESSION))
        session_start();
}
// Redirect if already logged in
if (isset($_SESSION['usuario_id'])) {
    header('Location: ' . APP_URL . '/dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- sftp-sync -->
    <link rel="icon" type="image/png" href="<?= defined('APP_URL') ? APP_URL : '' ?>/img/favicon.png?v=<?= time() ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= APP_URL ?>/../public/css/app.css" rel="stylesheet">
</head>

<body>
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-logo"><i class="fas fa-bread-slice"></i></div>
            <h4 class="text-center fw-bold mb-1"><?= APP_NAME ?></h4>
            <p class="text-center mb-4" style="color:var(--text-muted); font-size:13px;">Sistema de Gestión de Negocios
            </p>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger py-2 mb-3"
                    style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;border-radius:8px;font-size:13px;">
                    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['exito'])): ?>
                <div class="alert alert-success py-2 mb-3"
                    style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);color:#10b981;border-radius:8px;font-size:13px;">
                    <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['exito']) ?>
                </div>
                <?php unset($_SESSION['exito']); ?>
            <?php endif; ?>

            <form action="<?= APP_URL ?>/auth/proceso_login" method="POST">
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"
                            style="background:var(--bg-card2);border:1px solid var(--border);border-right:none;color:var(--text-muted);">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="correo" class="form-control" style="border-left:none;"
                            placeholder="admin@catnisbakery.com" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"
                            style="background:var(--bg-card2);border:1px solid var(--border);border-right:none;color:var(--text-muted);">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="contrasena" class="form-control" style="border-left:none;"
                            placeholder="••••••••" required>
                    </div>
                    <div class="text-end mt-2">
                        <a href="<?= APP_URL ?>/auth/olvido"
                            style="color:var(--text-muted);text-decoration:none;font-size:12px;">¿Olvidaste tu
                            contraseña?</a>
                    </div>
                </div>
                <button type="submit" class="btn-primary-custom w-100 mb-3">
                    <i class="fas fa-right-to-bracket me-2"></i>Iniciar Sesión
                </button>
            </form>
            <p class="text-center mt-3" style="font-size:11px;color:var(--text-dim);">
                Demo: admin@catnisbakery.com / password
            </p>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>