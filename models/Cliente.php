<?php
// ============================================================
// MODELO: Cliente
// ============================================================
require_once APP_ROOT . '/config/db.php';

class Cliente {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function todos(): array {
        return $this->db->query("SELECT * FROM clientes WHERE activo = 1 ORDER BY nombre")->fetchAll();
    }

    public function buscarPorId(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id = :id AND activo = 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear(array $datos): bool {
        $stmt = $this->db->prepare("INSERT INTO clientes (nombre, telefono, correo, direccion, nombre_mascota, cumpleanos_mascota) VALUES (:nombre,:telefono,:correo,:direccion,:nombre_mascota,:cumpleanos_mascota)");
        return $stmt->execute($datos);
    }

    public function actualizar(int $id, array $datos): bool {
        $stmt = $this->db->prepare("UPDATE clientes SET nombre=:nombre, telefono=:telefono, correo=:correo, direccion=:direccion, nombre_mascota=:nombre_mascota, cumpleanos_mascota=:cumpleanos_mascota WHERE id=:id");
        $datos[':id'] = $id;
        return $stmt->execute($datos);
    }

    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("UPDATE clientes SET activo = 0 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function historialVentas(int $clienteId): array {
        $stmt = $this->db->prepare("
            SELECT v.*, COUNT(dv.id) as num_productos
            FROM ventas v
            LEFT JOIN detalle_ventas dv ON dv.venta_id = v.id
            WHERE v.cliente_id = :cliente_id
            GROUP BY v.id
            ORDER BY v.fecha DESC
        ");
        $stmt->execute([':cliente_id' => $clienteId]);
        return $stmt->fetchAll();
    }
}
