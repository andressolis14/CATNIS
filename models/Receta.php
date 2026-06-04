<?php
require_once APP_ROOT . '/config/db.php';

class Receta {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function todas(): array {
        $stmt = $this->db->query("
            SELECT r.*, p.nombre as producto_nombre, p.precio_venta,
            (SELECT COUNT(*) FROM receta_insumos ri WHERE ri.receta_id = r.id) as total_insumos
            FROM recetas r
            JOIN productos p ON p.id = r.producto_id
            ORDER BY p.nombre ASC
        ");
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): array|false {
        $stmt = $this->db->prepare("
            SELECT r.*, p.nombre as producto_nombre
            FROM recetas r JOIN productos p ON p.id = r.producto_id
            WHERE r.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $receta = $stmt->fetch();
        if ($receta) {
            $receta['insumos'] = $this->insumosPorReceta($id);
        }
        return $receta;
    }

    public function buscarPorProducto(int $producto_id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM recetas WHERE producto_id = :pid");
        $stmt->execute([':pid' => $producto_id]);
        return $stmt->fetch();
    }

    public function insumosPorReceta(int $receta_id): array {
        $stmt = $this->db->prepare("
            SELECT ri.*, i.nombre as insumo_nombre, i.unidad_medida, i.stock as stock_actual
            FROM receta_insumos ri
            JOIN insumos i ON i.id = ri.insumo_id
            WHERE ri.receta_id = :id
        ");
        $stmt->execute([':id' => $receta_id]);
        return $stmt->fetchAll();
    }

    public function crear(array $receta, array $insumos): int|false {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("
                INSERT INTO recetas (producto_id, nombre, descripcion, rendimiento)
                VALUES (:producto_id, :nombre, :descripcion, :rendimiento)
            ");
            $stmt->execute($receta);
            $recetaId = (int)$this->db->lastInsertId();

            $stmtIns = $this->db->prepare("
                INSERT INTO receta_insumos (receta_id, insumo_id, cantidad)
                VALUES (:receta_id, :insumo_id, :cantidad)
            ");
            foreach ($insumos as $ins) {
                $stmtIns->execute([
                    ':receta_id' => $recetaId,
                    ':insumo_id' => $ins['insumo_id'],
                    ':cantidad'  => $ins['cantidad'],
                ]);
            }
            $this->db->commit();
            return $recetaId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error Receta::crear: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar(int $id, array $receta, array $insumos): bool {
        try {
            $this->db->beginTransaction();
            $receta[':id'] = $id;
            $stmt = $this->db->prepare("
                UPDATE recetas SET nombre=:nombre, descripcion=:descripcion, rendimiento=:rendimiento
                WHERE id=:id
            ");
            $stmt->execute($receta);

            $this->db->prepare("DELETE FROM receta_insumos WHERE receta_id = :id")->execute([':id' => $id]);

            $stmtIns = $this->db->prepare("
                INSERT INTO receta_insumos (receta_id, insumo_id, cantidad)
                VALUES (:receta_id, :insumo_id, :cantidad)
            ");
            foreach ($insumos as $ins) {
                $stmtIns->execute([
                    ':receta_id' => $id,
                    ':insumo_id' => $ins['insumo_id'],
                    ':cantidad'  => $ins['cantidad'],
                ]);
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM recetas WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
