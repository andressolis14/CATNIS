<!DOCTYPE html>
<html lang="es">

<head>
    <!-- sftp-sync -->
    <link rel="icon" type="image/png" href="<?= defined('APP_URL') ? APP_URL : '' ?>/img/favicon.png?v=<?= time() ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= APP_URL ?>/../public/css/app.css" rel="stylesheet">
    <script>
        // Aplicar el tema inmediatamente para evitar parpadeos
        const theme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', theme);
    </script>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-brand d-flex align-items-center px-4 py-4">
                <div class="brand-logo me-3" style="width: 45px; height: 45px; flex-shrink: 0;">
                    <img src="<?= APP_URL ?>/img/logo.png" alt="Catnis Bakery Logo"
                        style="width: 100%; height: 100%; object-fit: contain; border-radius: 0; display: block;">
                </div>
                <div>
                    <div class="brand-name">CATNIS</div>
                    <div class="brand-sub">BAKERY</div>
                </div>
            </div>

            <ul class="sidebar-nav list-unstyled px-3">
                <li class="nav-label">PRINCIPAL</li>
                <li>
                    <a href="<?= APP_URL ?>/dashboard"
                        class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'active' : '' ?>">
                        <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-label">VENTAS</li>
                <li>
                    <a href="<?= APP_URL ?>/ventas"
                        class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/ventas') !== false ? 'active' : '' ?>">
                        <i class="fas fa-shopping-cart"></i> <span>Ventas</span>
                    </a>
                </li>
                <li>
                    <a href="<?= APP_URL ?>/deudas"
                        class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/deudas') !== false ? 'active' : '' ?>">
                        <i class="fas fa-file-invoice-dollar"></i> <span>Cuentas por Cobrar</span>
                    </a>
                </li>
                <li class="nav-label">NEGOCIO</li>
                <li>
                    <a href="<?= APP_URL ?>/productos"
                        class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/productos') !== false ? 'active' : '' ?>">
                        <i class="fas fa-boxes-stacked"></i> <span>Inventario</span>
                    </a>
                </li>
                <li>
                    <a href="<?= APP_URL ?>/clientes"
                        class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/clientes') !== false ? 'active' : '' ?>">
                        <i class="fas fa-users"></i> <span>Clientes</span>
                    </a>
                </li>
                <li>
                    <a href="<?= APP_URL ?>/gastos"
                        class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/gastos') !== false ? 'active' : '' ?>">
                        <i class="fas fa-wallet"></i> <span>Gastos</span>
                    </a>
                </li>
                <li>
                    <a href="<?= APP_URL ?>/insumos"
                        class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/insumos') !== false ? 'active' : '' ?>">
                        <i class="fas fa-flask"></i> <span>Insumos</span>
                    </a>
                </li>
                <li>
                    <a href="<?= APP_URL ?>/recetas"
                        class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/recetas') !== false ? 'active' : '' ?>">
                        <i class="fas fa-book-open"></i> <span>Recetas</span>
                    </a>
                </li>
                <li>
                    <a href="<?= APP_URL ?>/caja"
                        class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/caja') !== false && strpos($_SERVER['REQUEST_URI'], '/historial') === false ? 'active' : '' ?>">
                        <i class="fas fa-cash-register"></i> <span>Caja Rápida</span>
                    </a>
                </li>
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                    <li>
                        <a href="<?= APP_URL ?>/caja/historial"
                            class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/caja/historial') !== false ? 'active' : '' ?>">
                            <i class="fas fa-history"></i> <span>Historial de Caja</span>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-label">ANÁLISIS</li>
                <li>
                    <a href="<?= APP_URL ?>/reportes"
                        class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/reportes') !== false ? 'active' : '' ?>">
                        <i class="fas fa-chart-line"></i> <span>Reportes</span>
                    </a>
                </li>

                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                    <li class="nav-label">SISTEMA</li>
                    <li>
                        <a href="<?= APP_URL ?>/usuarios"
                            class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/usuarios') !== false ? 'active' : '' ?>">
                            <i class="fas fa-user-gear"></i> <span>Usuarios</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="sidebar-footer px-3 py-3 mt-auto">
                <a href="<?= APP_URL ?>/auth/logout" class="nav-link logout-link">
                    <i class="fas fa-right-from-bracket"></i> <span>Cerrar Sesión</span>
                </a>
            </div>
        </nav>

        <!-- Overlay para cerrar sidebar en móvil -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Content -->
        <div class="main-content">
            <!-- Topbar -->
            <div class="topbar d-flex align-items-center px-4">
                <button class="sidebar-toggle me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-title flex-grow-1">
                    <?= $pageTitle ?? 'Dashboard' ?>
                </div>
                <div class="topbar-user d-flex align-items-center gap-3">
                    <!-- Theme Toggle -->
                    <button id="themeToggle" class="sidebar-toggle" title="Cambiar Tema">
                        <i class="fas fa-moon dark-icon"></i>
                        <i class="fas fa-sun light-icon" style="display:none;"></i>
                    </button>

                    <div class="d-flex align-items-center gap-2">
                        <div class="user-avatar">
                            <?= strtoupper(substr($_SESSION['nombre'] ?? 'U', 0, 1)) ?>
                        </div>
                        <span class="d-none d-md-inline"><?= htmlspecialchars($_SESSION['nombre'] ?? '') ?></span>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            <div class="px-4 pt-3">
                <?php if (!empty($_SESSION['exito'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['exito']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['exito']); ?>
                <?php endif; ?>
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
            </div>

            <!-- Page Content -->
            <div class="page-content px-4 pb-4">