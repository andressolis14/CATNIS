<?php
// ============================================================
// MODELO: Deuda (Cuentas por cobrar)
// ============================================================
require_once APP_ROOT . '/config/db.php';

class Deuda {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function todas(): array {
        return $this->db->query("
            SELECT d.*, c.nombre as cliente, c.telefono, v.fecha as fecha_venta
            FROM deudas d
            JOIN clientes c ON c.id = d.cliente_id
            JOIN ventas v ON v.id = d.venta_id
            ORDER BY d.estado ASC, d.fecha DESC
        ")->fetchAll();
    }

    public function pendientes(): array {
        return $this->db->query("
            SELECT d.*, c.nombre as cliente
            FROM deudas d JOIN clientes c ON c.id = d.cliente_id
            WHERE d.estado != 'pagada'
            ORDER BY d.fecha
        ")->fetchAll();
    }

    public function buscarPorId(int $id): array|false {
        $stmt = $this->db->prepare("SELECT d.*, c.nombre as cliente FROM deudas d JOIN clientes c ON c.id = d.cliente_id WHERE d.id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function abonar(int $deudaId, float $monto, string $metodo_pago = 'efectivo', string $nota = ''): bool {
        try {
            $this->db->beginTransaction();

            $deuda = $this->buscarPorId($deudaId);
            if (!$deuda || $deuda['estado'] === 'pagada') return false;

            $nuevoAbonado = $deuda['abonado'] + $monto;
            $nuevoSaldo   = $deuda['total'] - $nuevoAbonado;
            $estado       = $nuevoSaldo <= 0 ? 'pagada' : 'parcial';

            $stmt = $this->db->prepare("UPDATE deudas SET abonado=:abonado, saldo=:saldo, estado=:estado WHERE id=:id");
            $stmt->execute([':abonado' => $nuevoAbonado, ':saldo' => max($nuevoSaldo, 0), ':estado' => $estado, ':id' => $deudaId]);

            $stmtAbono = $this->db->prepare("INSERT INTO abonos (deuda_id, monto, metodo_pago, nota) VALUES (:deuda_id, :monto, :metodo_pago, :nota)");
            $stmtAbono->execute([
                ':deuda_id'   => $deudaId, 
                ':monto'      => $monto, 
                ':metodo_pago'=> $metodo_pago, // Usar la variable pasada por parámetro
                ':nota'       => $nota
            ]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function abonosPorDeuda(int $deudaId): array {
        $stmt = $this->db->prepare("SELECT * FROM abonos WHERE deuda_id = :id ORDER BY fecha DESC");
        $stmt->execute([':id' => $deudaId]);
        return $stmt->fetchAll();
    }

    public function totalPendiente(): float {
        return (float)$this->db->query("SELECT COALESCE(SUM(saldo),0) FROM deudas WHERE estado != 'pagada'")->fetchColumn();
    }

    public function totalAbonosPorMetodo(string $inicio, string $fin, string $metodo): float {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(monto),0) FROM abonos WHERE metodo_pago=:metodo AND fecha BETWEEN :inicio AND :fin");
        $stmt->execute([':inicio' => $inicio, ':fin' => $fin . ' 23:59:59', ':metodo' => $metodo]);
        return (float)$stmt->fetchColumn();
    }
}
