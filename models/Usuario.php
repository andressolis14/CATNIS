<?php
// ============================================================
// MODELO: Usuario
// ============================================================
require_once APP_ROOT . '/config/db.php';

class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function crear(array $datos): bool
    {
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (:nombre, :correo, :contrasena, :rol)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':correo' => $datos['correo'],
            ':contrasena' => password_hash($datos['contrasena'], PASSWORD_BCRYPT),
            ':rol' => $datos['rol'] ?? 'usuario',
        ]);
    }

    public function buscarPorCorreo(string $correo): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE correo = :correo AND activo = 1 LIMIT 1");
        $stmt->execute([':correo' => $correo]);
        return $stmt->fetch();
    }

    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function correoExiste(string $correo): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo");
        $stmt->execute([':correo' => $correo]);
        return $stmt->fetchColumn() > 0;
    }

    public function verificarContrasena(string $plana, string $hash): bool
    {
        return password_verify($plana, $hash);
    }

    public function guardarTokenRecuperacion(string $correo, string $token, string $expiracion): bool
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET reset_token = :token, reset_token_expira = :expira WHERE correo = :correo");
        return $stmt->execute([
            ':token' => $token,
            ':expira' => $expiracion,
            ':correo' => $correo
        ]);
    }

    public function buscarPorToken(string $token): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE reset_token = :token AND reset_token_expira > NOW() LIMIT 1");
        $stmt->execute([':token' => $token]);
        return $stmt->fetch();
    }

    public function actualizarContrasena(int $id, string $nueva): bool
    {
        $hash = password_hash($nueva, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE usuarios SET contrasena = :pass, reset_token = NULL, reset_token_expira = NULL WHERE id = :id");
        return $stmt->execute([':pass' => $hash, ':id' => $id]);
    }

    public function listarTodos(): array
    {
        $stmt = $this->db->prepare("SELECT id, nombre, correo, rol, activo, created_at FROM usuarios ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
