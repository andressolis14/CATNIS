<?php
require_once APP_ROOT . '/config/db.php';

class Insumo {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function todos(): array {
        return $this->db->query("SELECT * FROM insumos WHERE activo = 1 ORDER BY nombre ASC")->fetchAll();
    }

    public function buscarPorId(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM insumos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear(array $datos): int|false {
        $stmt = $this->db->prepare("
            INSERT INTO insumos (nombre, descripcion, unidad_medida, stock, stock_minimo, costo_unitario)
            VALUES (:nombre, :descripcion, :unidad_medida, :stock, :stock_minimo, :costo_unitario)
        ");
        if ($stmt->execute($datos)) return (int)$this->db->lastInsertId();
        return false;
    }

    public function actualizar(int $id, array $datos): bool {
        $datos[':id'] = $id;
        $stmt = $this->db->prepare("
            UPDATE insumos SET nombre=:nombre, descripcion=:descripcion, unidad_medida=:unidad_medida,
            stock=:stock, stock_minimo=:stock_minimo, costo_unitario=:costo_unitario
            WHERE id=:id
        ");
        return $stmt->execute($datos);
    }

    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("UPDATE insumos SET activo = 0 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function bajoStock(): array {
        return $this->db->query("SELECT * FROM insumos WHERE activo = 1 AND stock <= stock_minimo ORDER BY nombre ASC")->fetchAll();
    }

    public function todosParaSelect(): array {
        return $this->db->query("SELECT id, nombre, unidad_medida, stock, costo_unitario FROM insumos WHERE activo = 1 ORDER BY nombre ASC")->fetchAll();
    }
}
