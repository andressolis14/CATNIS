<!DOCTYPE html>
<html lang="es">

<head>
    <!-- sftp-sync -->
    <link rel="icon" type="image/png" href="<?= defined('APP_URL') ? APP_URL : '' ?>/img/favicon.png?v=<?= time() ?>">
    <meta charset="UTF-8">
    <title>Recibo #V-<?= $venta['id'] ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            margin: 0;
            padding: 20px;
            background: #f0f0f0;
        }

        .ticket {
            background: #fff;
            width: 300px;
            margin: 0 auto;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 5px 0;
            text-transform: uppercase;
        }

        .info {
            font-size: 12px;
            margin-bottom: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .table th {
            text-align: left;
            border-bottom: 1px solid #eee;
            padding: 5px 0;
        }

        .table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .totals {
            border-top: 1px dashed #ccc;
            padding-top: 10px;
            font-weight: bold;
        }

        .totals div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            font-style: italic;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .ticket {
                box-shadow: none;
                width: 100%;
                margin: 0;
            }

            .no-print {
                display: none;
            }
        }

        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn {
            background: #3b82f6;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="no-print">
        <button class="btn" onclick="window.print()">Imprimir Recibo</button>
        <a href="<?= APP_URL ?>/ventas/detalle?id=<?= $venta['id'] ?>" class="btn" style="background:#64748b">Volver</a>
    </div>

    <div class="ticket">
        <div class="header">
            <h2><?= APP_NAME ?></h2>
            <div>Pasión por el Pan</div>
            <div style="font-size: 11px; margin-top: 5px;"><?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></div>
        </div>

        <div class="info">
            <div><strong>TICKET:</strong> #V-<?= str_pad($venta['id'], 5, '0', STR_PAD_LEFT) ?></div>
            <div><strong>CLIENTE:</strong> <?= htmlspecialchars($venta['cliente_nombre'] ?: 'General') ?></div>
            <div><strong>VENDEDOR:</strong> <?= htmlspecialchars($venta['vendedor']) ?></div>
            <div><strong>TIPO:</strong> <?= strtoupper($venta['tipo']) ?></div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Cant.</th>
                    <th>Producto</th>
                    <th style="text-align:right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $d): ?>
                    <tr>
                        <td><?= $d['cantidad'] ?>x</td>
                        <td><?= htmlspecialchars($d['producto']) ?></td>
                        <td style="text-align:right">$<?= number_format($d['subtotal'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals">
            <div>
                <span>TOTAL:</span>
                <span>$<?= number_format($venta['total'], 2) ?></span>
            </div>
            <?php if ($venta['tipo'] === 'credito'): ?>
                <div style="font-size:12px; color: #ef4444;">
                    <span>PENDIENTE:</span>
                    <span>$<?= number_format($venta['total'], 2) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            Â¡Gracias por preferir Catnis Bakery!<br>
            ðŸ¥ðŸ¥ðŸ¥
        </div>
    </div>

</body>

</html>