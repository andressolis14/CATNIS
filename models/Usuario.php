<?php
// ============================================================
// MODELO: Usuario
// ============================================================
require_once APP_ROOT . '/config/db.php';

class Usuario {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function crear(array $datos): bool {
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (:nombre, :correo, :contrasena, :rol)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'     => $datos['nombre'],
            ':correo'     => $datos['correo'],
            ':contrasena' => password_hash($datos['contrasena'], PASSWORD_BCRYPT),
            ':rol'        => $datos['rol'] ?? 'usuario',
        ]);
    }

    public function buscarPorCorreo(string $correo): array|false {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE correo = :correo AND activo = 1 LIMIT 1");
        $stmt->execute([':correo' => $correo]);
        return $stmt->fetch();
    }

    public function buscarPorId(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function correoExiste(string $correo): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo");
        $stmt->execute([':correo' => $correo]);
        return $stmt->fetchColumn() > 0;
    }

    public function verificarContrasena(string $plana, string $hash): bool {
        return password_verify($plana, $hash);
    }
}
