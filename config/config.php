<?php
// ============================================================
// CONFIGURACIÓN DE BASE DE DATOS
// ============================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'catnis_bakery');
define('DB_CHARSET', 'utf8mb4');

// Configuración de la aplicación
define('APP_NAME', 'CATNIS BAKERY');
define('APP_URL', 'http://localhost/CATNIS%20BAKERY/public');
define('APP_ROOT', dirname(__DIR__));
define('WHATSAPP_NUMBER', '573041317929'); // <-- CAMBIA ESTO POR TU NÚMERO (Incluye código de país)

// Zona horaria
date_default_timezone_set('America/El_Salvador');
