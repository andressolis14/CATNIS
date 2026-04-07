<?php
// ============================================================
// MODELO: Gasto
// ============================================================
require_once APP_ROOT . '/config/db.php';

class Gasto {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function todos(array $filtros = []): array {
        $where = ['1=1'];
        $params = [];
        if (!empty($filtros['categoria'])) {
            $where[] = 'categoria = :categoria';
            $params[':categoria'] = $filtros['categoria'];
        }
        if (!empty($filtros['fecha_inicio'])) {
            $where[] = 'fecha >= :fecha_inicio';
            $params[':fecha_inicio'] = $filtros['fecha_inicio'];
        }
        if (!empty($filtros['fecha_fin'])) {
            $where[] = 'fecha <= :fecha_fin';
            $params[':fecha_fin'] = $filtros['fecha_fin'];
        }
        $stmt = $this->db->prepare("SELECT g.*, u.nombre as registrado_por FROM gastos g JOIN usuarios u ON u.id = g.usuario_id WHERE " . implode(' AND ', $where) . " ORDER BY fecha DESC");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM gastos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $gasto = $stmt->fetch();
        if ($gasto) {
            $stmtDet = $this->db->prepare("SELECT * FROM detalle_gastos WHERE gasto_id = :id");
            $stmtDet->execute([':id' => $id]);
            $gasto['detalles'] = $stmtDet->fetchAll();
        }
        return $gasto;
    }

    public function crear(array $gasto, array $items): int|false {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO gastos (usuario_id, numero_factura, monto, categoria, fecha, descripcion) VALUES (:usuario_id, :numero_factura, :monto, :categoria, :fecha, :descripcion)");
            $stmt->execute($gasto);
            $gastoId = (int)$this->db->lastInsertId();

            $stmtDet = $this->db->prepare("INSERT INTO detalle_gastos (gasto_id, descripcion, cantidad, monto) VALUES (:gasto_id, :descripcion, :cantidad, :monto)");
            foreach ($items as $item) {
                $stmtDet->execute([
                    ':gasto_id'    => $gastoId,
                    ':descripcion' => $item['descripcion'],
                    ':cantidad'    => $item['cantidad'] ?? 1,
                    ':monto'       => $item['monto']
                ]);
            }

            $this->db->commit();
            return $gastoId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en Gasto::crear: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar(int $id, array $gasto, array $items): bool {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("UPDATE gastos SET numero_factura=:numero_factura, monto=:monto, categoria=:categoria, fecha=:fecha, descripcion=:descripcion WHERE id=:id");
            $gasto[':id'] = $id;
            $stmt->execute($gasto);

            // Eliminar items previos y re-insertar
            $this->db->prepare("DELETE FROM detalle_gastos WHERE gasto_id = :id")->execute([':id' => $id]);
            
            $stmtDet = $this->db->prepare("INSERT INTO detalle_gastos (gasto_id, descripcion, cantidad, monto) VALUES (:gasto_id, :descripcion, :cantidad, :monto)");
            foreach ($items as $item) {
                $stmtDet->execute([
                    ':gasto_id'    => $id,
                    ':descripcion' => $item['descripcion'],
                    ':cantidad'    => $item['cantidad'] ?? 1,
                    ':monto'       => $item['monto']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en Gasto::actualizar: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM gastos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function totalPorPeriodo(string $inicio, string $fin): float {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(monto),0) FROM gastos WHERE fecha BETWEEN :inicio AND :fin");
        $stmt->execute([':inicio' => $inicio, ':fin' => $fin]);
        return (float)$stmt->fetchColumn();
    }

    public function gastosPorCategoria(): array {
        return $this->db->query("SELECT categoria, SUM(monto) as total FROM gastos GROUP BY categoria ORDER BY total DESC")->fetchAll();
    }

    public function detallesPorGasto(int $id): array {
        $stmt = $this->db->prepare("SELECT * FROM detalle_gastos WHERE gasto_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll();
    }
}
