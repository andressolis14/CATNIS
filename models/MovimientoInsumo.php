<?php
require_once APP_ROOT . '/config/db.php';

class MovimientoInsumo {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function todos(array $filtros = []): array {
        $where  = ['1=1'];
        $params = [];
        if (!empty($filtros['insumo_id'])) {
            $where[] = 'm.insumo_id = :insumo_id';
            $params[':insumo_id'] = $filtros['insumo_id'];
        }
        if (!empty($filtros['tipo'])) {
            $where[] = 'm.tipo = :tipo';
            $params[':tipo'] = $filtros['tipo'];
        }
        if (!empty($filtros['fecha_inicio'])) {
            $where[] = 'm.fecha >= :fecha_inicio';
            $params[':fecha_inicio'] = $filtros['fecha_inicio'];
        }
        if (!empty($filtros['fecha_fin'])) {
            $where[] = 'm.fecha <= :fecha_fin';
            $params[':fecha_fin'] = $filtros['fecha_fin'];
        }
        $stmt = $this->db->prepare("
            SELECT m.*, i.nombre as insumo_nombre, i.unidad_medida, u.nombre as usuario_nombre
            FROM movimientos_insumos m
            JOIN insumos i ON i.id = m.insumo_id
            JOIN usuarios u ON u.id = m.usuario_id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY m.fecha DESC, m.created_at DESC
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function registrar(array $datos): bool {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO movimientos_insumos (insumo_id, tipo, cantidad, descripcion, usuario_id, fecha)
                VALUES (:insumo_id, :tipo, :cantidad, :descripcion, :usuario_id, :fecha)
            ");
            $stmt->execute($datos);

            $esEntrada = in_array($datos[':tipo'], ['entrada', 'ajuste']);
            $sql = $esEntrada
                ? "UPDATE insumos SET stock = stock + :cantidad WHERE id = :id"
                : "UPDATE insumos SET stock = stock - :cantidad WHERE id = :id";

            $this->db->prepare($sql)->execute([
                ':cantidad' => $datos[':cantidad'],
                ':id'       => $datos[':insumo_id'],
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error MovimientoInsumo::registrar: " . $e->getMessage());
            return false;
        }
    }
}
