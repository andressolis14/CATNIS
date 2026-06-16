<?php
// ============================================================
// PUNTO DE ENTRADA PRINCIPAL - ROUTER BÁSICO
// ============================================================
session_start();

// Evitar caché del navegador en todas las páginas
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

// Autoload de controllers y models
spl_autoload_register(function ($class) {
    $paths = [
        APP_ROOT . '/controllers/' . $class . '.php',
        APP_ROOT . '/models/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// ---- AUTO-LOGIN ADMIN PERMANENTE ----
if (!isset($_SESSION['usuario_id']) && isset($_COOKIE['admin_remember'])) {
    $userModel = new Usuario();
    $adminUser = $userModel->buscarPorCorreo('catnisbakery@gmail.com');
    if ($adminUser) {
        $_SESSION['usuario_id'] = $adminUser['id'];
        $_SESSION['nombre'] = $adminUser['nombre'];
        $_SESSION['rol'] = $adminUser['rol'];
        $_SESSION['correo'] = $adminUser['correo'];
    }
}

// ---- CONTROL DE INACTIVIDAD (20 minutos) ----
if (isset($_SESSION['usuario_id'])) {
    $limite_inactividad = 20 * 60; // 1200 segundos

    $es_admin_inmune = (isset($_SESSION['correo']) && $_SESSION['correo'] === 'catnisbakery@gmail.com');

    if (!$es_admin_inmune && isset($_SESSION['ultima_actividad']) && (time() - $_SESSION['ultima_actividad'] > $limite_inactividad)) {
        session_unset();
        session_destroy();
        session_start(); // Reiniciar para poder guardar el mensaje de error
        $_SESSION['error'] = 'Tu sesión ha expirado por inactividad.';
        header('Location: ' . APP_URL . '/auth/login');
        exit;
    }
    $_SESSION['ultima_actividad'] = time(); // Actualizar pulso de actividad
}

// ---- DETECTAR RUTA ----
// Quitar el script del REQUEST_URI y obtener solo el path lógico
$scriptDir = dirname($_SERVER['SCRIPT_NAME']); // ej: /CATNIS BAKERY/public
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Decodificar %20 y quitar el prefijo del subdirectorio
$requestUri = urldecode($requestUri);
$scriptDir = urldecode($scriptDir);

$path = ltrim(substr($requestUri, strlen($scriptDir)), '/');

// ---- RUTAS PÚBLICAS (sin sesión requerida) ----
$publicRoutes = [
    'auth/login',
    'auth/proceso_login',
    'auth/olvido',
    'auth/proceso_olvido',
    'auth/restablecer',
    'auth/proceso_restablecer',
    'catalogo',
    'catalogo/cartelera',
];

if (!isset($_SESSION['usuario_id']) && !in_array($path, $publicRoutes)) {
    header('Location: ' . APP_URL . '/auth/login');
    exit;
}

// ---- ENRUTAMIENTO ----
$segments = explode('/', $path);
$controller = strtolower($segments[0] ?? '');
$action = strtolower($segments[1] ?? 'index');

// Controller por defecto si está vacío o es raíz
if ($controller === '') {
    $controller = 'dashboard';
    $action = 'index';
}

// Mapa controlador => clase
$controllerMap = [
    'dashboard' => 'DashboardController',
    'auth' => 'AuthController',
    'productos' => 'ProductoController',
    'clientes' => 'ClienteController',
    'ventas' => 'VentaController',
    'deudas' => 'DeudaController',
    'gastos' => 'GastoController',
    'reportes' => 'ReporteController',
    'catalogo' => 'CatalogoController',
    'usuarios' => 'UsuariosController', // Nueva ruta limpia para gestión de usuarios
    'caja'    => 'CajaController',
    'insumos'     => 'InsumoController',
    'recetas'     => 'RecetaController',
    'movimientos' => 'MovimientoInsumoController',
    'produccion'  => 'ProduccionController',
];

if (!array_key_exists($controller, $controllerMap)) {
    http_response_code(404);
    require_once APP_ROOT . '/views/errors/404.php';
    exit;
}

$ctrlClass = $controllerMap[$controller];
$ctrlFile = APP_ROOT . '/controllers/' . $ctrlClass . '.php';

if (!file_exists($ctrlFile)) {
    http_response_code(404);
    require_once APP_ROOT . '/views/errors/404.php';
    exit;
}

require_once $ctrlFile;
$ctrl = new $ctrlClass();

if (!method_exists($ctrl, $action)) {
    // Si no existe el action, fallback a index
    if (method_exists($ctrl, 'index')) {
        $ctrl->index();
    } else {
        http_response_code(404);
        require_once APP_ROOT . '/views/errors/404.php';
    }
    exit;
}

$ctrl->$action();
