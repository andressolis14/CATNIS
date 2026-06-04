<?php
// ============================================================
// CONTROLADOR: Deudas
// ============================================================
require_once APP_ROOT . '/models/Deuda.php';

class DeudaController
{
    private Deuda $model;

    public function __construct()
    {
        $this->model = new Deuda();
    }

    public function index(): void
    {
        $deudas = $this->model->todas();

        // Calcular antigüedad para cada deuda
        foreach ($deudas as $key => $d) {
            $fechaDeuda = new DateTime($d['fecha']);
            $hoy = new DateTime(date('Y-m-d'));
            $diff = $hoy->diff($fechaDeuda);
            $deudas[$key]['dias_antiguedad'] = $diff->days;

            // Generar link de WhatsApp para cobro
            $msg = "Hola " . $d['cliente'] . ", te saludamos de *" . APP_NAME . "* 🥐. Te recordamos que tienes un saldo pendiente de *$" . number_format($d['saldo'], 2) . "*. ¿Cuándo podrías pasar a abonar? ¡Quedamos atentos! 😊";
            $deudas[$key]['wa_link'] = "https://wa.me/" . preg_replace('/[^0-9]/', '', $d['telefono']) . "?text=" . urlencode($msg);
        }

        require_once APP_ROOT . '/views/deudas/index.php';
    }

    public function detalle(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $deuda = $this->model->buscarPorId($id);
        if (!$deuda) {
            header('Location: ' . APP_URL . '/deudas');
            exit;
        }
        $abonos = $this->model->abonosPorDeuda($id);
        require_once APP_ROOT . '/views/deudas/detalle.php';
    }

    public function abonar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/deudas');
            exit;
        }
        $id = (int) ($_POST['deuda_id'] ?? 0);
        $monto = (float) ($_POST['monto'] ?? 0);
        $metodo_pago = $_POST['metodo_pago'] ?? 'efectivo';
        $nota = trim($_POST['nota'] ?? '');

        if ($monto <= 0) {
            $_SESSION['error'] = 'El monto debe ser mayor a 0.';
            header('Location: ' . APP_URL . '/deudas/detalle?id=' . $id);
            exit;
        }

        $deuda = $this->model->buscarPorId($id);
        if ($deuda && $monto > $deuda['saldo']) {
            $_SESSION['error'] = 'El abono no puede superar el saldo pendiente ($' . number_format($deuda['saldo'], 2) . ').';
            header('Location: ' . APP_URL . '/deudas/detalle?id=' . $id);
            exit;
        }

        if ($this->model->abonar($id, $monto, $metodo_pago, $nota)) {
            $_SESSION['exito'] = 'Abono de $' . number_format($monto, 2) . ' registrado correctamente.';
        } else {
            $_SESSION['error'] = 'Error al registrar el abono.';
        }
        header('Location: ' . APP_URL . '/deudas/detalle?id=' . $id);
        exit;
    }
}
