<?php
require_once APP_ROOT . '/config/db.php';
require_once APP_ROOT . '/models/Receta.php';

class Produccion {
    private PDO $db;
    private Receta $recetaModel;

    public function __construct() {
        $this->db = getDB();
        $this->recetaModel = new Receta();
    }

    public function todas(): array {
        return $this->db->query("
            SELECT p.*, r.nombre as receta_nombre, pr.nombre as producto_nombre, u.nombre as usuario_nombre
            FROM producciones p
            JOIN recetas r ON r.id = p.receta_id
            JOIN productos pr ON pr.id = r.producto_id
            JOIN usuarios u ON u.id = p.usuario_id
            ORDER BY p.fecha DESC, p.created_at DESC
        ")->fetchAll();
    }

    public function registrar(int $receta_id, float $cantidad, int $usuario_id, string $notas, string $fecha): bool {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO producciones (receta_id, cantidad_producida, usuario_id, notas, fecha)
                VALUES (:receta_id, :cantidad, :usuario_id, :notas, :fecha)
            ");
            $stmt->execute([
                ':receta_id'  => $receta_id,
                ':cantidad'   => $cantidad,
                ':usuario_id' => $usuario_id,
                ':notas'      => $notas,
                ':fecha'      => $fecha,
            ]);
            $produccionId = (int)$this->db->lastInsertId();

            $insumos   = $this->recetaModel->insumosPorReceta($receta_id);
            $stmtMov   = $this->db->prepare("
                INSERT INTO movimientos_insumos (insumo_id, tipo, cantidad, descripcion, usuario_id, fecha)
                VALUES (:insumo_id, 'salida', :cantidad, :descripcion, :usuario_id, :fecha)
            ");
            $stmtStock = $this->db->prepare("UPDATE insumos SET stock = stock - :cantidad WHERE id = :id");

            foreach ($insumos as $i) {
                $cant = round((float)$i['cantidad'] * $cantidad, 2);
                $stmtMov->execute([
                    ':insumo_id'   => $i['insumo_id'],
                    ':cantidad'    => $cant,
                    ':descripcion' => "Producción #$produccionId — {$cantidad} und. de {$i['insumo_nombre']}",
                    ':usuario_id'  => $usuario_id,
                    ':fecha'       => $fecha,
                ]);
                $stmtStock->execute([':cantidad' => $cant, ':id' => $i['insumo_id']]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error Produccion::registrar: " . $e->getMessage());
            return false;
        }
    }
}
