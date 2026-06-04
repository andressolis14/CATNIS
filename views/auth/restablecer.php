<?php
if (!defined('APP_NAME')) {
    require_once dirname(__DIR__, 2) . '/config/config.php';
    require_once dirname(__DIR__, 2) . '/config/db.php';
    if (!isset($_SESSION))
        session_start();
}
$token = $_GET['token'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- sftp-sync -->
    <link rel="icon" type="image/png" href="<?= defined('APP_URL') ? APP_URL : '' ?>/img/favicon.png?v=<?= time() ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Establecer Nueva Contraseña - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= APP_URL ?>/../public/css/app.css" rel="stylesheet">
</head>

<body>
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-logo"><i class="fas fa-shield-halved"></i></div>
            <h4 class="text-center fw-bold mb-1">Nueva Contraseña</h4>
            <p class="text-center mb-4" style="color:var(--text-muted); font-size:13px;">Escribe tu nueva clave de
                acceso.</p>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger py-2 mb-3"
                    style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;border-radius:8px;font-size:13px;">
                    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="<?= APP_URL ?>/auth/proceso_restablecer" method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="mb-3">
                    <label class="form-label">Nueva Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"
                            style="background:var(--bg-card2);border:1px solid var(--border);border-right:none;color:var(--text-muted);">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="contrasena" class="form-control" style="border-left:none;"
                            placeholder="Mínimo 6 caracteres" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirmar Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"
                            style="background:var(--bg-card2);border:1px solid var(--border);border-right:none;color:var(--text-muted);">
                            <i class="fas fa-check-double"></i>
                        </span>
                        <input type="password" name="confirmar" class="form-control" style="border-left:none;"
                            placeholder="Repite la contraseña" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary-custom w-100 mb-3">
                    <i class="fas fa-save me-2"></i>Actualizar Contraseña
                </button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>