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

            $stmt = $this->db->prepare("INSERT INTO gastos (usuario_id, numero_factura, monto, categoria, fecha, descripcion, metodo_pago, proveedor) VALUES (:usuario_id, :numero_factura, :monto, :categoria, :fecha, :descripcion, :metodo_pago, :proveedor)");
            $stmt->execute($gasto);
            $gastoId = (int)$this->db->lastInsertId();

            $stmtDet = $this->db->prepare("INSERT INTO detalle_gastos (gasto_id, descripcion, unidad_medida, cantidad, monto, item_maestro_id) VALUES (:gasto_id, :descripcion, :unidad_medida, :cantidad, :monto, :item_maestro_id)");
            foreach ($items as $item) {
                $stmtDet->execute([
                    ':gasto_id'        => $gastoId,
                    ':descripcion'     => $item['descripcion'],
                    ':unidad_medida'   => $item['unidad_medida'] ?? 'unid',
                    ':cantidad'        => $item['cantidad'] ?? 1,
                    ':monto'           => $item['monto'],
                    ':item_maestro_id' => $item['item_maestro_id'] ?? null
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

            $stmt = $this->db->prepare("UPDATE gastos SET numero_factura=:numero_factura, monto=:monto, categoria=:categoria, fecha=:fecha, descripcion=:descripcion, metodo_pago=:metodo_pago, proveedor=:proveedor WHERE id=:id");
            $gasto[':id'] = $id;
            $stmt->execute($gasto);

            // Eliminar items previos y re-insertar
            $this->db->prepare("DELETE FROM detalle_gastos WHERE gasto_id = :id")->execute([':id' => $id]);
            
            $stmtDet = $this->db->prepare("INSERT INTO detalle_gastos (gasto_id, descripcion, unidad_medida, cantidad, monto, item_maestro_id) VALUES (:gasto_id, :descripcion, :unidad_medida, :cantidad, :monto, :item_maestro_id)");
            foreach ($items as $item) {
                $stmtDet->execute([
                    ':gasto_id'        => $id,
                    ':descripcion'     => $item['descripcion'],
                    ':unidad_medida'   => $item['unidad_medida'] ?? 'unid',
                    ':cantidad'        => $item['cantidad'] ?? 1,
                    ':monto'           => $item['monto'],
                    ':item_maestro_id' => $item['item_maestro_id'] ?? null
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

    public function existeFactura(string $numero): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM gastos WHERE numero_factura = :numero AND numero_factura != ''");
        $stmt->execute([':numero' => $numero]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM gastos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function totalPorMetodoPago(string $metodo, string $inicio, string $fin): float {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(monto),0) FROM gastos WHERE metodo_pago = :metodo AND fecha BETWEEN :inicio AND :fin");
        $stmt->execute([':metodo' => $metodo, ':inicio' => $inicio, ':fin' => $fin . ' 23:59:59']);
        return (float)$stmt->fetchColumn();
    }

    public function totalPorPeriodo(string $inicio, string $fin): float {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(monto),0) FROM gastos WHERE fecha BETWEEN :inicio AND :fin");
        $stmt->execute([':inicio' => $inicio, ':fin' => $fin . ' 23:59:59']);
        return (float)$stmt->fetchColumn();
    }

    public function gastosPorPeriodoAgrupados(string $inicio, string $fin): array {
        $stmt = $this->db->prepare("
            SELECT DATE(fecha) as fecha_dia, SUM(monto) as total
            FROM gastos WHERE fecha BETWEEN :inicio AND :fin
            GROUP BY DATE(fecha) ORDER BY fecha_dia
        ");
        $stmt->execute([':inicio' => $inicio, ':fin' => $fin . ' 23:59:59']);
        return $stmt->fetchAll();
    }

    public function gastosPorCategoria(string $inicio, string $fin): array {
        $stmt = $this->db->prepare("SELECT categoria, SUM(monto) as total FROM gastos WHERE fecha BETWEEN :inicio AND :fin GROUP BY categoria ORDER BY total DESC");
        $stmt->execute([':inicio' => $inicio, ':fin' => $fin . ' 23:59:59']);
        return $stmt->fetchAll();
    }

    public function detallesPorGasto(int $id): array {
        $stmt = $this->db->prepare("
            SELECT d.*, m.codigo as codigo_maestro 
            FROM detalle_gastos d 
            LEFT JOIN items_maestro_gastos m ON d.item_maestro_id = m.id 
            WHERE d.gasto_id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll();
    }
}
