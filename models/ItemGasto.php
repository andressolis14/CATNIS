<?php
// ============================================================
// MODELO: ItemGasto (Maestro de Items)
// ============================================================
require_once APP_ROOT . '/config/db.php';

class ItemGasto {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function todos(): array {
        return $this->db->query("SELECT * FROM items_maestro_gastos ORDER BY nombre ASC")->fetchAll();
    }

    public function crear(string $nombre, ?string $codigo = null, string $unidad = 'unid'): int|false {
        if (!$codigo) {
            // Generar un código basado en el nombre + sufijo único
            $cleanName = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $nombre), 0, 4));
            $codigo = 'ITEM-' . $cleanName . '-' . strtoupper(substr(uniqid(), -4));
        }
        $stmt = $this->db->prepare("INSERT INTO items_maestro_gastos (codigo, nombre, unidad_medida) VALUES (:codigo, :nombre, :unidad)");
        if ($stmt->execute([
            ':codigo' => $codigo, 
            ':nombre' => $nombre,
            ':unidad' => $unidad
        ])) {
            return (int)$this->db->lastInsertId();
        }
        return false;
    }
    
    public function buscarPorNombre(string $nombre): array|false {
        $stmt = $this->db->prepare("SELECT * FROM items_maestro_gastos WHERE nombre = :nombre LIMIT 1");
        $stmt->execute([':nombre' => $nombre]);
        return $stmt->fetch();
    }

    public function obtenerOcrear(string $nombre): int {
        $item = $this->buscarPorNombre($nombre);
        if ($item) return (int)$item['id'];
        return $this->crear($nombre);
    }

    public function buscarPorId(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM items_maestro_gastos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function actualizar(int $id, string $nombre, string $codigo, string $unidad): bool {
        $stmt = $this->db->prepare("UPDATE items_maestro_gastos SET nombre = :nombre, codigo = :codigo, unidad_medida = :unidad WHERE id = :id");
        return $stmt->execute([
            ':id'     => $id, 
            ':nombre' => $nombre, 
            ':codigo' => $codigo,
            ':unidad' => $unidad
        ]);
    }

    public function eliminar(int $id): bool {
        // Nota: Solo se podrá eliminar si no tiene dependencias en detalle_gastos 
        // o si permitimos que queden como NULL.
        $stmt = $this->db->prepare("DELETE FROM items_maestro_gastos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
