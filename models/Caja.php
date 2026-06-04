<?php
// ============================================================
// MODELO: Caja
// ============================================================
require_once APP_ROOT . '/config/db.php';

class Caja
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // ==========================================
    // NUEVAS FUNCIONES DE TURNOS (SESIONES)
    // ==========================================

    public function obtenerSesionActiva()
    {
        $stmt = $this->db->prepare("SELECT * FROM sesiones_caja WHERE estado = 'abierta' LIMIT 1");
        $stmt->execute();
        return $stmt->fetch();
    }

    public function abrirCaja($usuario_id, $m_ef, $m_ba, $obs = '')
    {
        $stmt = $this->db->prepare("
            INSERT INTO sesiones_caja (usuario_id, monto_inicial_efectivo, monto_inicial_banco, observaciones, estado) 
            VALUES (:uid, :m_ef, :m_ba, :obs, 'abierta')
        ");
        return $stmt->execute([
            ':uid' => $usuario_id,
            ':m_ef' => $m_ef,
            ':m_ba' => $m_ba,
            ':obs' => $obs
        ]);
    }

    public function calcularTotalesSesion($sesion_id)
    {
        $sesion = $this->db->prepare("SELECT * FROM sesiones_caja WHERE id = ?");
        $sesion->execute([$sesion_id]);
        $s = $sesion->fetch();

        if (!$s)
            return null;

        $inicio = $s['fecha_apertura'];
        $fin = date('Y-m-d H:i:s');

        // Ventas Efectivo y Banco en ese periodo
        $v_ef = $this->db->prepare("SELECT COALESCE(SUM(total), 0) FROM ventas WHERE metodo_pago = 'efectivo' AND fecha BETWEEN ? AND ?");
        $v_ef->execute([$inicio, $fin]);
        $ventas_ef = $v_ef->fetchColumn();

        $v_ba = $this->db->prepare("SELECT COALESCE(SUM(total), 0) FROM ventas WHERE metodo_pago = 'transferencia' AND fecha BETWEEN ? AND ?");
        $v_ba->execute([$inicio, $fin]);
        $ventas_ba = $v_ba->fetchColumn();

        // Gastos en ese periodo — separados por método de pago
        $g_ef = $this->db->prepare("SELECT COALESCE(SUM(monto), 0) FROM gastos WHERE metodo_pago = 'efectivo' AND fecha BETWEEN ? AND ?");
        $g_ef->execute([$inicio, $fin]);
        $gastos_ef = $g_ef->fetchColumn();

        $g_ba = $this->db->prepare("SELECT COALESCE(SUM(monto), 0) FROM gastos WHERE metodo_pago = 'transferencia' AND fecha BETWEEN ? AND ?");
        $g_ba->execute([$inicio, $fin]);
        $gastos_ba = $g_ba->fetchColumn();

        // Abonos
        $a_ef = $this->db->prepare("SELECT COALESCE(SUM(monto), 0) FROM abonos WHERE metodo_pago = 'efectivo' AND fecha BETWEEN ? AND ?");
        $a_ef->execute([$inicio, $fin]);
        $abonos_ef = $a_ef->fetchColumn();

        $a_ba = $this->db->prepare("SELECT COALESCE(SUM(monto), 0) FROM abonos WHERE metodo_pago = 'transferencia' AND fecha BETWEEN ? AND ?");
        $a_ba->execute([$inicio, $fin]);
        $abonos_ba = $a_ba->fetchColumn();

        // Movimientos directos de caja (Ingresos/Egresos manuales)
        $m_ef = $this->db->prepare("
            SELECT 
                COALESCE(SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE 0 END), 0) -
                COALESCE(SUM(CASE WHEN tipo = 'egreso' THEN monto ELSE 0 END), 0)
            FROM caja_movimientos WHERE metodo_pago = 'efectivo' AND fecha BETWEEN ? AND ?
        ");
        $m_ef->execute([$inicio, $fin]);
        $mov_ef = $m_ef->fetchColumn();

        $m_ba = $this->db->prepare("
            SELECT 
                COALESCE(SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE 0 END), 0) -
                COALESCE(SUM(CASE WHEN tipo = 'egreso' THEN monto ELSE 0 END), 0)
            FROM caja_movimientos WHERE metodo_pago = 'transferencia' AND fecha BETWEEN ? AND ?
        ");
        $m_ba->execute([$inicio, $fin]);
        $mov_ba = $m_ba->fetchColumn();

        // Ventas a Crédito (No afectan el efectivo pero son informativas)
        $v_cr = $this->db->prepare("SELECT COALESCE(SUM(total), 0) FROM ventas WHERE tipo = 'credito' AND fecha BETWEEN ? AND ?");
        $v_cr->execute([$inicio, $fin]);
        $ventas_cr = (float) $v_cr->fetchColumn();

        // Saldo Pendiente de lo que se fió HOY
        $s_pe = $this->db->prepare("SELECT COALESCE(SUM(saldo), 0) FROM deudas WHERE fecha BETWEEN ? AND ?");
        $s_pe->execute([date('Y-m-d', strtotime($inicio)), date('Y-m-d', strtotime($fin))]);
        $saldo_cr = (float) $s_pe->fetchColumn();

        $esperado_ef = $s['monto_inicial_efectivo'] + $ventas_ef + $abonos_ef + $mov_ef - $gastos_ef;
        $esperado_ba = $s['monto_inicial_banco'] + $ventas_ba + $abonos_ba + $mov_ba - $gastos_ba;

        return [
            'base_ef'    => $s['monto_inicial_efectivo'],
            'base_ba'    => $s['monto_inicial_banco'],
            'ventas_ef'  => $ventas_ef,
            'ventas_ba'  => $ventas_ba,
            'ventas_cr'  => $ventas_cr,
            'saldo_cr'   => $saldo_cr,
            'gastos_ef'  => $gastos_ef,
            'gastos_ba'  => $gastos_ba,
            'gastos'     => $gastos_ef + $gastos_ba,
            'abonos_ef'  => $abonos_ef,
            'abonos_ba'  => $abonos_ba,
            'mov_ef'     => $mov_ef,
            'mov_ba'     => $mov_ba,
            'esperado_ef' => $esperado_ef,
            'esperado_ba' => $esperado_ba
        ];
    }

    public function cerrarCaja($sesion_id, $real_ef, $real_ba, $obs = '')
    {
        $totales = $this->calcularTotalesSesion($sesion_id);
        $dif_ef = $real_ef - $totales['esperado_ef'];
        $dif_ba = $real_ba - $totales['esperado_ba'];

        $stmt = $this->db->prepare("
            UPDATE sesiones_caja SET 
                fecha_cierre = CURRENT_TIMESTAMP,
                monto_esperado_efectivo = :e_ef,
                monto_esperado_banco = :e_ba,
                monto_real_efectivo = :r_ef,
                monto_real_banco = :r_ba,
                diferencia_efectivo = :d_ef,
                diferencia_banco = :d_ba,
                estado = 'cerrada',
                observaciones = :obs
            WHERE id = :id
        ");
        return $stmt->execute([
            ':e_ef' => $totales['esperado_ef'],
            ':e_ba' => $totales['esperado_ba'],
            ':r_ef' => $real_ef,
            ':r_ba' => $real_ba,
            ':d_ef' => $dif_ef,
            ':d_ba' => $dif_ba,
            ':obs' => $obs,
            ':id' => $sesion_id
        ]);
    }

    public function historialSesiones()
    {
        $stmt = $this->db->prepare("
            SELECT s.*, u.nombre as abierto_por,
            (SELECT COALESCE(SUM(total), 0) FROM ventas WHERE tipo = 'credito' AND fecha BETWEEN s.fecha_apertura AND COALESCE(s.fecha_cierre, NOW())) as ventas_credito,
            (SELECT COALESCE(SUM(saldo), 0) FROM deudas WHERE fecha BETWEEN DATE(s.fecha_apertura) AND DATE(COALESCE(s.fecha_cierre, NOW())) 
             AND venta_id IN (SELECT id FROM ventas WHERE fecha BETWEEN s.fecha_apertura AND COALESCE(s.fecha_cierre, NOW()))) as saldo_credito
            FROM sesiones_caja s
            JOIN usuarios u ON u.id = s.usuario_id
            ORDER BY s.fecha_apertura DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerMovimientosCompletosSesion($sesion_id)
    {
        $sesion = $this->db->prepare("SELECT fecha_apertura, fecha_cierre FROM sesiones_caja WHERE id = ?");
        $sesion->execute([$sesion_id]);
        $s = $sesion->fetch();
        if (!$s)
            return [];

        $inicio = $s['fecha_apertura'];
        $fin = $s['fecha_cierre'] ?? date('Y-m-d H:i:s');

        $sql = "
            (SELECT fecha, 'venta' as tipo, CONCAT('Venta #', id, ' (', metodo_pago, ')') as descripcion, total as monto FROM ventas WHERE fecha BETWEEN :ini1 AND :fin1)
            UNION ALL
            (SELECT fecha, 'gasto' as tipo, descripcion, monto FROM gastos WHERE fecha BETWEEN :ini2 AND :fin2)
            UNION ALL
            (SELECT fecha, 'abono' as tipo, CONCAT('Abono: ', COALESCE(nota, 'Sin Nota')) as descripcion, monto FROM abonos WHERE fecha BETWEEN :ini3 AND :fin3)
            UNION ALL
            (SELECT fecha, tipo, descripcion, monto FROM caja_movimientos WHERE fecha BETWEEN :ini4 AND :fin4)
            ORDER BY fecha DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':ini1' => $inicio,
            ':fin1' => $fin,
            ':ini2' => $inicio,
            ':fin2' => $fin,
            ':ini3' => $inicio,
            ':fin3' => $fin,
            ':ini4' => $inicio,
            ':fin4' => $fin
        ]);
        return $stmt->fetchAll();
    }

    // ==========================================
    // FUNCIONES ANTERIORES (Actualizadas)
    // ==========================================

    public function todos(): array
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.nombre as registrado_por 
            FROM caja_movimientos c 
            JOIN usuarios u ON u.id = c.usuario_id 
            ORDER BY c.fecha DESC LIMIT 50
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function crear(array $datos): int|false
    {
        try {
            $fecha = $datos['fecha'] ?? date('Y-m-d H:i:s');
            $stmt = $this->db->prepare("
                INSERT INTO caja_movimientos (usuario_id, tipo, metodo_pago, monto, descripcion, fecha) 
                VALUES (:usuario_id, :tipo, :metodo_pago, :monto, :descripcion, :fecha)
            ");
            $stmt->execute([
                ':usuario_id' => $datos['usuario_id'],
                ':tipo' => $datos['tipo'],
                ':metodo_pago' => $datos['metodo_pago'],
                ':monto' => $datos['monto'],
                ':descripcion' => $datos['descripcion'],
                ':fecha' => $fecha
            ]);
            return (int) $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Error en Caja::crear: " . $e->getMessage());
            return false;
        }
    }

    public function reabrirSesion(int $sesion_id): bool
    {
        // Solo reabrir si no hay otra sesión activa
        $activa = $this->obtenerSesionActiva();
        if ($activa) return false;

        $stmt = $this->db->prepare("
            UPDATE sesiones_caja SET
                estado       = 'abierta',
                fecha_cierre = NULL
            WHERE id = :id AND estado = 'cerrada'
        ");
        return $stmt->execute([':id' => $sesion_id]);
    }

    public function actualizarSesion(int $sesion_id, float $real_ef, float $real_ba, string $obs): bool
    {
        $sesion = $this->db->prepare("SELECT monto_esperado_efectivo, monto_esperado_banco FROM sesiones_caja WHERE id = ?");
        $sesion->execute([$sesion_id]);
        $s = $sesion->fetch();
        if (!$s) return false;

        $dif_ef = $real_ef - $s['monto_esperado_efectivo'];
        $dif_ba = $real_ba - $s['monto_esperado_banco'];

        $stmt = $this->db->prepare("
            UPDATE sesiones_caja SET
                monto_real_efectivo  = :r_ef,
                monto_real_banco     = :r_ba,
                diferencia_efectivo  = :d_ef,
                diferencia_banco     = :d_ba,
                observaciones        = :obs
            WHERE id = :id
        ");
        return $stmt->execute([
            ':r_ef' => $real_ef,
            ':r_ba' => $real_ba,
            ':d_ef' => $dif_ef,
            ':d_ba' => $dif_ba,
            ':obs'  => $obs,
            ':id'   => $sesion_id,
        ]);
    }

    public function obtenerSesionPorId(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM sesiones_caja WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM caja_movimientos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function calcularSaldoNetoMetodo(string $metodo_pago): float
    {
        $stmt = $this->db->prepare("
            SELECT 
                COALESCE(SUM(CASE WHEN tipo IN ('ingreso', 'apertura') THEN monto ELSE 0 END), 0) -
                COALESCE(SUM(CASE WHEN tipo = 'egreso' THEN monto ELSE 0 END), 0) as saldo
            FROM caja_movimientos
            WHERE metodo_pago = :metodo
        ");
        $stmt->execute([':metodo' => $metodo_pago]);
        return (float) $stmt->fetchColumn();
    }

    public function calcularSaldoNetoMetodoRango(string $metodo_pago, string $inicio, string $fin): float
    {
        $stmt = $this->db->prepare("
            SELECT 
                COALESCE(SUM(CASE WHEN tipo IN ('ingreso', 'apertura') THEN monto ELSE 0 END), 0) -
                COALESCE(SUM(CASE WHEN tipo = 'egreso' THEN monto ELSE 0 END), 0) as saldo
            FROM caja_movimientos
            WHERE metodo_pago = :metodo AND fecha BETWEEN :inicio AND :fin
        ");
        $stmt->execute([':metodo' => $metodo_pago, ':inicio' => $inicio, ':fin' => $fin . ' 23:59:59']);
        return (float) $stmt->fetchColumn();
    }
}
