<?php
if (!defined('APP_NAME')) {
    require_once dirname(__DIR__, 2) . '/config/config.php';
    require_once dirname(__DIR__, 2) . '/config/db.php';
    if (!isset($_SESSION))
        session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- sftp-sync -->
    <link rel="icon" type="image/png" href="<?= defined('APP_URL') ? APP_URL : '' ?>/img/favicon.png?v=<?= time() ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= APP_URL ?>/../public/css/app.css" rel="stylesheet">
</head>

<body>
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-logo"><i class="fas fa-key"></i></div>
            <h4 class="text-center fw-bold mb-1">¿Olvidaste tu contraseña?</h4>
            <p class="text-center mb-4" style="color:var(--text-muted); font-size:13px;">No te preocupes, dinos tu
                correo y te enviaremos un enlace.</p>

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

            <form action="<?= APP_URL ?>/auth/proceso_olvido" method="POST">
                <div class="mb-4">
                    <label class="form-label">Correo electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"
                            style="background:var(--bg-card2);border:1px solid var(--border);border-right:none;color:var(--text-muted);">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="correo" class="form-control" style="border-left:none;"
                            placeholder="nombre@ejemplo.com" required autofocus>
                    </div>
                </div>
                <button type="submit" class="btn-primary-custom w-100 mb-3">
                    <i class="fas fa-paper-plane me-2"></i>Enviar enlace seguro
                </button>
            </form>
            <p class="text-center mb-0" style="font-size:13px;color:var(--text-muted);">
                ¿Te acordaste? <a href="<?= APP_URL ?>/auth/login"
                    style="color:var(--accent);text-decoration:none;">Volver al login</a>
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>