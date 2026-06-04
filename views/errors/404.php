<?php http_response_code(404); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- sftp-sync -->
    <link rel="icon" type="image/png" href="<?= defined('APP_URL') ? APP_URL : '' ?>/img/favicon.png?v=<?= time() ?>">
    <meta charset="UTF-8">
    <title>404 - Página no encontrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #131720;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #f1f5f9;
        }

        .error-box {
            text-align: center;
        }

        .error-num {
            font-size: 120px;
            font-weight: 700;
            color: rgba(245, 158, 11, 0.3);
            line-height: 1;
        }

        h3 {
            color: #f1f5f9;
            margin: 16px 0 8px;
        }

        p {
            color: #94a3b8;
            margin-bottom: 24px;
        }

        a {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="error-box">
        <div class="error-num">404</div>
        <h3>Página no encontrada</h3>
        <p>La ruta que buscas no existe en el sistema.</p>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <a href="<?= APP_URL ?>/dashboard">â† Ir al Dashboard</a>
        <?php else: ?>
            <a href="<?= APP_URL ?>/auth/login">â† Iniciar Sesión</a>
        <?php endif; ?>
    </div>
</body>

</html>