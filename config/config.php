<?php
// ============================================================
// CONFIGURACIÓN DE BASE DE DATOS
// ============================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'catnisba_CATNISBAKERY');
define('DB_PASS', 'C4tn1sH0s92*/');
define('DB_NAME', 'catnisba_tuusuario_catnis');
define('DB_CHARSET', 'utf8mb4');

// Configuración de la aplicación
define('APP_NAME', 'CATNIS BAKERY');
define('APP_URL', 'https://catnisbakery.com/public');
define('APP_ROOT', dirname(__DIR__));
define('WHATSAPP_NUMBER', '573248773971'); // <-- CAMBIA ESTO POR TU NÚMERO (Incluye código de país)
define('MAIL_SENDER', 'soporte@catnisbakery.com'); // <-- El correo que crees en cPanel

// Zona horaria
date_default_timezone_set('America/Bogota');
