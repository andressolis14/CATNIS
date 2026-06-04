<?php
// ============================================================
// CONEXIÓN A BASE DE DATOS CON PDO
// ============================================================
require_once __DIR__ . '/config.php';

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

            // Sincronizar zona horaria de MySQL con PHP
            $offset = date('P'); // Obtiene el offset ej: -06:00
            $this->pdo->exec("SET time_zone = '$offset'");

        } catch (PDOException $e) {
            die(json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]));
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}

// Función helper global
function getDB(): PDO
{
    return Database::getInstance()->getConnection();
}
