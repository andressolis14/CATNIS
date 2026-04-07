<?php
// ============================================================
// MODELO: Venta
// ============================================================
require_once APP_ROOT . '/config/db.php';

class Venta {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function todas(): array {
        return $this->db->query("
            SELECT v.*, u.nombre as vendedor, c.nombre as cliente_nombre
            FROM ventas v
            JOIN usuarios u ON u.id = v.usuario_id
            LEFT JOIN clientes c ON c.id = v.cliente_id
            ORDER BY v.fecha DESC
        ")->fetchAll();
    }

    public function crear(array $venta, array $detalles): int|false {
        try {
            $this->db->beginTransaction();

            // Insertar venta
            $stmt = $this->db->prepare("INSERT INTO ventas (usuario_id, cliente_id, tipo, total, metodo_pago, estado, notas, fecha) VALUES (:usuario_id,:cliente_id,:tipo,:total,:metodo_pago,:estado,:notas,:fecha)");
            $stmt->execute($venta);
            $ventaId = (int)$this->db->lastInsertId();

            // Insertar detalles y reducir stock
            $stmtDet = $this->db->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (:venta_id,:producto_id,:cantidad,:precio_unitario,:subtotal)");
            $stmtStock = $this->db->prepare("UPDATE productos SET stock = stock - :cant1 WHERE id = :id AND stock >= :cant2");

            foreach ($detalles as $d) {
                $stmtDet->execute([
                    ':venta_id'       => $ventaId,
                    ':producto_id'    => $d['producto_id'],
                    ':cantidad'       => $d['cantidad'],
                    ':precio_unitario'=> $d['precio_unitario'],
                    ':subtotal'       => $d['subtotal'],
                ]);
                $stmtStock->execute([
                    ':cant1' => $d['cantidad'], 
                    ':cant2' => $d['cantidad'], 
                    ':id'    => $d['producto_id']
                ]);
            }

            // Si es crédito, crear deuda
            if ($venta[':tipo'] === 'credito' && !empty($venta[':cliente_id'])) {
                $stmtDeuda = $this->db->prepare("INSERT INTO deudas (venta_id, cliente_id, total, saldo, fecha) VALUES (:venta_id, :cliente_id, :total, :saldo, :fecha)");
                $stmtDeuda->execute([
                    ':venta_id'   => $ventaId,
                    ':cliente_id' => $venta[':cliente_id'],
                    ':total'      => $venta[':total'],
                    ':saldo'      => $venta[':total'],
                    ':fecha'      => date('Y-m-d', strtotime($venta[':fecha'])), // Usar la misma fecha manual
                ]);
            }

            $this->db->commit();
            return $ventaId;
        } catch (Exception $e) {
            $this->db->rollBack();
            // Para depuración rápida, guardamos en el log de Apache
            error_log("Error en Venta::crear: " . $e->getMessage());
            // Opcional: mostrar el error real en la sesión para que el usuario nos diga
            if (isset($_SESSION)) {
                $_SESSION['error_db'] = $e->getMessage();
            }
            return false;
        }
    }

    public function buscarPorId(int $id): array|false {
        $stmt = $this->db->prepare("
            SELECT v.*, u.nombre as vendedor, c.nombre as cliente_nombre
            FROM ventas v JOIN usuarios u ON u.id = v.usuario_id
            LEFT JOIN clientes c ON c.id = v.cliente_id
            WHERE v.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function detallesPorVenta(int $ventaId): array {
        $stmt = $this->db->prepare("
            SELECT dv.*, p.nombre as producto
            FROM detalle_ventas dv
            JOIN productos p ON p.id = dv.producto_id
            WHERE dv.venta_id = :venta_id
        ");
        $stmt->execute([':venta_id' => $ventaId]);
        return $stmt->fetchAll();
    }

    public function totalPorPeriodo(string $inicio, string $fin): float {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(total),0) FROM ventas WHERE estado='completada' AND fecha BETWEEN :inicio AND :fin");
        $stmt->execute([':inicio' => $inicio, ':fin' => $fin . ' 23:59:59']);
        return (float)$stmt->fetchColumn();
    }

    public function ventasPorDia(int $mes, int $anio): array {
        $stmt = $this->db->prepare("
            SELECT DAY(fecha) as dia, SUM(total) as total
            FROM ventas WHERE MONTH(fecha)=:mes AND YEAR(fecha)=:anio AND estado='completada'
            GROUP BY DAY(fecha) ORDER BY dia
        ");
        $stmt->execute([':mes' => $mes, ':anio' => $anio]);
        return $stmt->fetchAll();
    }

    public function totalPorMetodo(string $inicio, string $fin, string $metodo): float {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(total),0) FROM ventas WHERE estado='completada' AND metodo_pago=:metodo AND fecha BETWEEN :inicio AND :fin");
        $stmt->execute([':inicio' => $inicio, ':fin' => $fin . ' 23:59:59', ':metodo' => $metodo]);
        return (float)$stmt->fetchColumn();
    }
}
