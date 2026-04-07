<?php
$pageTitle = 'Recibo de Gasto';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between no-print">
    <div>
        <a href="<?= APP_URL ?>/gastos" style="color:var(--accent);text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Volver a gastos</a>
        <h4 class="mt-2"><i class="fas fa-receipt me-2" style="color:var(--accent)"></i>Recibo de Gasto #<?= str_pad($gasto['id'], 5, '0', STR_PAD_LEFT) ?></h4>
    </div>
    <button onclick="exportarPDF()" class="btn-primary-custom">
        <i class="fas fa-download me-2"></i>Descargar PDF
    </button>
</div>

<div id="recibo-template" style="padding: 20px; background: #fff; color: #000; font-family: 'Inter', sans-serif; max-width: 800px; margin: 0 auto; border: 1px solid #eee; border-radius: 8px;">
    <!-- Encabezado -->
    <div style="text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px;">
        <h1 style="margin: 0; font-size: 24px; color: #1a1f2e; text-transform: uppercase;">CATNIS BAKERY</h1>
        <p style="margin: 5px 0; font-size: 12px; color: #666;">Repostería Canina de Lujo | RUC: EX-0000000000</p>
        <div style="margin-top: 10px; font-weight: bold; background: #f9f9f9; display: inline-block; padding: 5px 15px; border-radius: 5px;">
            COMPROBANTE DE GASTO
        </div>
    </div>

    <!-- Info del Gasto -->
    <div style="display: flex; justify-content: space-between; margin-bottom: 25px; font-size: 13px;">
        <div>
            <p style="margin: 0 0 5px;"><strong>N° Registro:</strong> #RG-<?= str_pad($gasto['id'], 5, '0', STR_PAD_LEFT) ?></p>
            <p style="margin: 0 0 5px;"><strong>N° Factura Proveedor:</strong> <?= htmlspecialchars($gasto['numero_factura'] ?: 'S/N') ?></p>
            <p style="margin: 0;"><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($gasto['fecha'])) ?></p>
        </div>
        <div style="text-align: right;">
            <p style="margin: 0 0 5px;"><strong>Categoría:</strong> <span style="text-transform: uppercase;"><?= $gasto['categoria'] ?></span></p>
            <p style="margin: 0;"><strong>Registrado por:</strong> <?= htmlspecialchars($_SESSION['nombre'] ?? 'Administrador') ?></p>
        </div>
    </div>

    <?php if (!empty($gasto['descripcion'])): ?>
    <div style="background: #fdfdfd; border-left: 3px solid #ccc; padding: 10px; margin-bottom: 25px; font-size: 13px; font-style: italic;">
        <strong>Nota:</strong> <?= htmlspecialchars($gasto['descripcion']) ?>
    </div>
    <?php endif; ?>

    <!-- Tabla de Ítems -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
        <thead>
            <tr style="background: #1a1f2e; color: #fff;">
                <th style="padding: 10px; text-align: left; font-size: 11px; border-radius: 5px 0 0 5px;">DESCRIPCIÓN</th>
                <th style="padding: 10px; text-align: center; font-size: 11px; width: 60px;">CANT.</th>
                <th style="padding: 10px; text-align: right; font-size: 11px; width: 100px;">P. UNIT.</th>
                <th style="padding: 10px; text-align: right; font-size: 11px; border-radius: 0 5px 5px 0; width: 100px;">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($gasto['detalles'])): ?>
                <?php foreach ($gasto['detalles'] as $idx => $det): ?>
                    <?php $subtotal = ($det['cantidad'] ?? 1) * $det['monto']; ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 10px; font-size: 12px;"><?= htmlspecialchars($det['descripcion']) ?></td>
                        <td style="padding: 12px 10px; text-align: center; font-size: 12px;"><?= $det['cantidad'] ?? 1 ?></td>
                        <td style="padding: 12px 10px; text-align: right; font-size: 12px;">$<?= number_format($det['monto'], 0, ',', '.') ?></td>
                        <td style="padding: 12px 10px; text-align: right; font-size: 12px; font-weight: bold;">$<?= number_format($subtotal, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 10px; font-size: 13px;">Gasto general</td>
                    <td style="padding: 12px 10px; text-align: right; font-size: 13px; font-weight: bold;">$<?= number_format($gasto['monto'], 0, ',', '.') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="padding: 20px 10px 10px; text-align: right; font-weight: bold; font-size: 11px; color: #666;">TOTAL GENERAL</td>
                <td style="padding: 20px 10px 10px; text-align: right; font-size: 18px; font-weight: 800; color: #000; border-top: 2px solid #000;">
                    $<?= number_format($gasto['monto'], 0, ',', '.') ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Firma / Footer -->
    <div style="margin-top: 50px; text-align: center; font-size: 10px; color: #999;">
        <p style="margin: 0;">CATNIS BAKERY | Software de Gestión Interna</p>
        <p style="margin: 5px 0;">Este documento es un comprobante interno de registro de gastos.</p>
        <p style="margin: 0;">Generado el <?= date('d/m/Y H:i:s') ?></p>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function exportarPDF() {
    const element = document.getElementById('recibo-template');
    const opt = {
        margin:       10,
        filename:     'Recibo_Gasto_<?= $gasto['id'] ?>.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save();
}
</script>

<style>
@media print {
    .no-print { display: none !important; }
}
</style>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
