<?php
// ============================================================
// PUNTO DE ENTRADA PRINCIPAL - ROUTER BÁSICO
// ============================================================
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

// Autoload de controllers y models
spl_autoload_register(function ($class) {
    $paths = [
        APP_ROOT . '/controllers/' . $class . '.php',
        APP_ROOT . '/models/'      . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// ---- DETECTAR RUTA ----
// Quitar el script del REQUEST_URI y obtener solo el path lógico
$scriptDir = dirname($_SERVER['SCRIPT_NAME']); // ej: /CATNIS BAKERY/public
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Decodificar %20 y quitar el prefijo del subdirectorio
$requestUri = urldecode($requestUri);
$scriptDir  = urldecode($scriptDir);

$path = ltrim(substr($requestUri, strlen($scriptDir)), '/');

// ---- RUTAS PÚBLICAS (sin sesión requerida) ----
$publicRoutes = [
    '',
    'auth/login',
    'auth/register',
    'auth/proceso_login',
    'auth/proceso_registro',
    'catalogo', // Nueva ruta pública
];

if (!isset($_SESSION['usuario_id']) && !in_array($path, $publicRoutes)) {
    header('Location: ' . APP_URL . '/auth/login');
    exit;
}

// ---- ENRUTAMIENTO ----
$segments   = explode('/', $path);
$controller = strtolower($segments[0] ?? '');
$action     = strtolower($segments[1] ?? 'index');

// Controller por defecto si está vacío o es raíz
if ($controller === '') {
    $controller = 'dashboard';
    $action     = 'index';
}

// Mapa controlador => clase
$controllerMap = [
    'dashboard' => 'DashboardController',
    'auth'      => 'AuthController',
    'productos' => 'ProductoController',
    'clientes'  => 'ClienteController',
    'ventas'    => 'VentaController',
    'deudas'    => 'DeudaController',
    'gastos'    => 'GastoController',
    'reportes'  => 'ReporteController',
    'catalogo'  => 'CatalogoController', // Registro del nuevo controlador
];

if (!array_key_exists($controller, $controllerMap)) {
    http_response_code(404);
    require_once APP_ROOT . '/views/errors/404.php';
    exit;
}

$ctrlClass = $controllerMap[$controller];
$ctrlFile  = APP_ROOT . '/controllers/' . $ctrlClass . '.php';

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
