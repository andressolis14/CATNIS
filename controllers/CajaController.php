<?php
// ============================================================
// CONTROLADOR: Caja y Capital
// ============================================================
require_once APP_ROOT . '/models/Caja.php';

class CajaController
{
    private Caja $caja;

    public function __construct()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }
        $this->caja = new Caja();
    }

    public function index(): void
    {
        $sesion = $this->caja->obtenerSesionActiva();

        if (!$sesion) {
            // Si no hay sesión, cargamos la vista de APERTURA
            require_once APP_ROOT . '/views/caja/apertura.php';
        } else {
            // Si hay sesión, cargamos el MONITOR DE TURNO
            $movimientos = $this->caja->obtenerMovimientosCompletosSesion($sesion['id']);
            $totales = $this->caja->calcularTotalesSesion($sesion['id']);
            require_once APP_ROOT . '/views/caja/index.php';
        }
    }

    public function abrir(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/caja');
            exit;
        }

        $m_ef = (float) ($_POST['monto_inicial_efectivo'] ?? 0);
        $m_ba = (float) ($_POST['monto_inicial_banco'] ?? 0);
        $obs = trim($_POST['observaciones'] ?? '');

        if ($this->caja->abrirCaja($_SESSION['usuario_id'], $m_ef, $m_ba, $obs)) {
            $_SESSION['exito'] = '¡Caja abierta exitosamente! Ya puedes empezar a vender.';
        } else {
            $_SESSION['error'] = 'Error al intentar abrir la caja.';
        }

        header('Location: ' . APP_URL . '/caja');
        exit;
    }

    public function cerrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/caja');
            exit;
        }

        $sesion_id = (int) ($_POST['sesion_id'] ?? 0);
        $r_ef = (float) ($_POST['monto_real_efectivo'] ?? 0);
        $r_ba = (float) ($_POST['monto_real_banco'] ?? 0);
        $obs = trim($_POST['observaciones'] ?? '');

        if ($this->caja->cerrarCaja($sesion_id, $r_ef, $r_ba, $obs)) {
            $_SESSION['exito'] = '¡Caja cerrada y arqueo guardado correctamente!';
        } else {
            $_SESSION['error'] = 'Ocurrió un error al procesar el cierre de caja.';
        }

        header('Location: ' . APP_URL . '/caja');
        exit;
    }

    public function historial(): void
    {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . APP_URL . '/caja');
            exit;
        }

        $sesiones = $this->caja->historialSesiones();
        require_once APP_ROOT . '/views/caja/historial.php';
    }

    public function guardar(): void
    {
        $sesion = $this->caja->obtenerSesionActiva();
        if (!$sesion) {
            $_SESSION['error'] = 'Debes abrir la caja antes de registrar movimientos.';
            header('Location: ' . APP_URL . '/caja');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/caja');
            exit;
        }

        $datos = [
            'usuario_id' => $_SESSION['usuario_id'],
            'tipo' => $_POST['tipo'] ?? 'ingreso',
            'metodo_pago' => $_POST['metodo_pago'] ?? 'efectivo',
            'monto' => (float) ($_POST['monto'] ?? 0),
            'descripcion' => trim($_POST['descripcion'] ?? '')
        ];

        if ($datos['monto'] <= 0) {
            $_SESSION['error'] = 'El monto debe ser mayor a 0.';
        } elseif (empty($datos['descripcion'])) {
            $_SESSION['error'] = 'Debes escribir una descripción.';
        } else {
            if ($this->caja->crear($datos)) {
                $_SESSION['exito'] = 'Movimiento registrado correctamente.';
            } else {
                $_SESSION['error'] = 'Ocurrió un error al registrar el movimiento.';
            }
        }

        header('Location: ' . APP_URL . '/caja');
        exit;
    }

    public function eliminar(): void
    {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'Solo los administradores pueden eliminar registros.';
            header('Location: ' . APP_URL . '/caja');
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            if ($this->caja->eliminar($id)) {
                $_SESSION['exito'] = 'Movimiento eliminado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al eliminar el movimiento.';
            }
        }
        header('Location: ' . APP_URL . '/caja');
        exit;
    }

    public function reabrir(): void
    {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'Solo los administradores pueden reabrir sesiones.';
            header('Location: ' . APP_URL . '/caja/historial');
            exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'Sesión inválida.';
            header('Location: ' . APP_URL . '/caja/historial');
            exit;
        }

        if ($this->caja->reabrirSesion($id)) {
            $_SESSION['exito'] = 'Sesión reabierta. Ya puedes revisar el turno y cerrarlo correctamente.';
            header('Location: ' . APP_URL . '/caja');
        } else {
            $_SESSION['error'] = 'No se pudo reabrir. Verifica que no haya otra caja activa.';
            header('Location: ' . APP_URL . '/caja/historial');
        }
        exit;
    }

    public function editarSesion(): void
    {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'Solo los administradores pueden editar sesiones.';
            header('Location: ' . APP_URL . '/caja/historial');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/caja/historial');
            exit;
        }

        $id     = (int)($_POST['sesion_id'] ?? 0);
        $r_ef   = (float)($_POST['monto_real_efectivo'] ?? 0);
        $r_ba   = (float)($_POST['monto_real_banco'] ?? 0);
        $obs    = trim($_POST['observaciones'] ?? '');

        if ($id <= 0) {
            $_SESSION['error'] = 'Sesión inválida.';
            header('Location: ' . APP_URL . '/caja/historial');
            exit;
        }

        if ($this->caja->actualizarSesion($id, $r_ef, $r_ba, $obs)) {
            $_SESSION['exito'] = 'Arqueo corregido correctamente.';
        } else {
            $_SESSION['error'] = 'Error al actualizar el arqueo.';
        }

        header('Location: ' . APP_URL . '/caja/historial');
        exit;
    }

    public function inyectarCapital(): void
    {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'Solo los administradores pueden registrar capital inicial.';
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }

        $fecha = $_POST['fecha'] . ' ' . date('H:i:s');
        $monto_ef = (float) ($_POST['monto_efectivo'] ?? 0);
        $monto_ba = (float) ($_POST['monto_banco'] ?? 0);

        $success = true;

        if ($monto_ef > 0) {
            $ok = $this->caja->crear([
                'usuario_id' => $_SESSION['usuario_id'],
                'tipo' => 'ingreso',
                'metodo_pago' => 'efectivo',
                'monto' => $monto_ef,
                'descripcion' => 'Inyección de Capital / Saldo Inicial',
                'fecha' => $fecha
            ]);
            if (!$ok)
                $success = false;
        }

        if ($monto_ba > 0) {
            $ok = $this->caja->crear([
                'usuario_id' => $_SESSION['usuario_id'],
                'tipo' => 'ingreso',
                'metodo_pago' => 'transferencia',
                'monto' => $monto_ba,
                'descripcion' => 'Inyección de Capital / Saldo Inicial',
                'fecha' => $fecha
            ]);
            if (!$ok)
                $success = false;
        }

        if ($success) {
            $_SESSION['exito'] = 'Capital inicial registrado correctamente para la fecha ' . $_POST['fecha'];
        } else {
            $_SESSION['error'] = 'Hubo un problema al registrar el capital.';
        }

        header('Location: ' . APP_URL . '/dashboard');
        exit;
    }
}
