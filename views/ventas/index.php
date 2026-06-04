<?php
$pageTitle = 'Historial de Ventas';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h4><i class="fas fa-shopping-cart me-2" style="color:var(--accent)"></i>Ventas</h4>
        <p>Historial de todas las ventas</p>
    </div>
    <button class="btn-primary-custom" style="border:none;cursor:pointer;" data-bs-toggle="modal" data-bs-target="#modalTipoVenta">
        <i class="fas fa-plus me-2"></i>Nueva Venta
    </button>
</div>

<div class="table-card">
    <?php if (empty($ventas)): ?>
        <div class="p-5 text-center">
            <i class="fas fa-receipt fa-3x mb-3" style="color:var(--text-dim)"></i>
            <h6 style="color:var(--text-muted)">Sin ventas registradas</h6>
            <a href="<?= APP_URL ?>/ventas/crear" class="btn-primary-custom mt-3 d-inline-block">Registrar primera venta</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table" id="ventasTable">
                <thead>
                    <tr>
                        <th class="sortable" data-column="0"># <i class="fas fa-sort ms-1"
                                style="font-size:11px;opacity:0.5"></i></th>
                        <th class="sortable" data-column="1">Fecha <i class="fas fa-sort ms-1"
                                style="font-size:11px;opacity:0.5"></i></th>
                        <th class="sortable" data-column="2">Cliente <i class="fas fa-sort ms-1"
                                style="font-size:11px;opacity:0.5"></i></th>
                        <th class="sortable" data-column="3">Tipo <i class="fas fa-sort ms-1"
                                style="font-size:11px;opacity:0.5"></i></th>
                        <th class="sortable" data-column="4">Pago <i class="fas fa-sort ms-1"
                                style="font-size:11px;opacity:0.5"></i></th>
                        <th class="sortable text-end" data-column="5">Total <i class="fas fa-sort ms-1"
                                style="font-size:11px;opacity:0.5"></i></th>
                        <th class="sortable" data-column="6">Estado <i class="fas fa-sort ms-1"
                                style="font-size:11px;opacity:0.5"></i></th>
                        <th class="text-end">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td class="text-dim" data-value="<?= $v['id'] ?>"><?= $v['id'] ?></td>
                            <td class="text-muted" style="font-size:13px" data-value="<?= $v['fecha'] ?>">
                                <?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                            <td class="fw-600 text-main"><?= htmlspecialchars($v['cliente_nombre'] ?? 'General') ?></td>
                            <td data-value="<?= $v['tipo'] ?>"><span
                                    class="badge-<?= $v['tipo'] ?>"><?= ucfirst($v['tipo'] === 'contado' ? 'Cont.' : 'Créd.') ?></span>
                            </td>
                            <td data-value="<?= $v['metodo_pago'] ?>">
                                <?php if ($v['metodo_pago'] === 'transferencia'): ?>
                                    <span class="text-blue" title="Transferencia/Banco"><i class="fas fa-university"></i></span>
                                <?php elseif ($v['metodo_pago'] === 'efectivo'): ?>
                                    <span class="text-green" title="Efectivo"><i class="fas fa-money-bill-wave"></i></span>
                                <?php else: ?>
                                    <span class="text-dim" title="Otros"><i class="fas fa-wallet"></i></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-bold text-green" data-value="<?= $v['total'] ?>">
                                $<?= number_format($v['total'], 0, ',', '.') ?></td>
                            <td data-value="<?= $v['estado'] ?>"><span
                                    class="badge-<?= $v['estado'] ?>"><?= ucfirst($v['estado']) ?></span></td>
                            <td class="text-end">
                                <a href="<?= APP_URL ?>/ventas/recibo?id=<?= $v['id'] ?>" class="btn-sm-icon me-1"
                                    style="background:rgba(245,158,11,0.1);color:var(--accent);" title="Ver Recibo"
                                    target="_blank">
                                    <i class="fas fa-receipt"></i>
                                </a>
                                <a href="<?= APP_URL ?>/ventas/detalle?id=<?= $v['id'] ?>" class="btn-sm-icon"
                                    style="background:rgba(59,130,246,0.15);color:var(--accent-blue);" title="Ver Detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($_SESSION['rol'] === 'admin'): ?>
                                    <a href="<?= APP_URL ?>/ventas/editar?id=<?= $v['id'] ?>" class="btn-sm-icon ms-1"
                                        style="background:rgba(16,185,129,0.15);color:var(--accent-green);" title="Editar Venta">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
    .sortable {
        cursor: pointer;
        transition: background 0.2s;
        position: relative;
    }

    .sortable:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .sortable i {
        transition: all 0.2s;
    }

    .sort-asc i {
        transform: rotate(180deg);
        color: var(--accent) !important;
        opacity: 1 !important;
    }

    .sort-desc i {
        transform: rotate(0deg);
        color: var(--accent) !important;
        opacity: 1 !important;
    }
</style>

<!-- Modal: Tipo de Venta -->
<div class="modal fade" id="modalTipoVenta" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:var(--bg-card);border:1px solid var(--border);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color:var(--text-main)">
                    <i class="fas fa-shopping-cart me-2" style="color:var(--accent)"></i>¿Qué tipo de venta es?
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3 pb-4">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/ventas/crear?modo=local" class="text-decoration-none">
                            <div class="p-4 rounded-3 text-center" style="border:2px solid var(--accent);background:rgba(245,158,11,0.08);cursor:pointer;transition:all .2s"
                                onmouseover="this.style.background='rgba(245,158,11,0.18)'" onmouseout="this.style.background='rgba(245,158,11,0.08)'">
                                <i class="fas fa-store fa-2x mb-3" style="color:var(--accent)"></i>
                                <div class="fw-bold" style="color:var(--text-main)">Venta en Local</div>
                                <div class="small mt-1" style="color:var(--text-muted)">Requiere caja abierta</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/ventas/crear?modo=externo" class="text-decoration-none">
                            <div class="p-4 rounded-3 text-center" style="border:2px solid #3b82f6;background:rgba(59,130,246,0.08);cursor:pointer;transition:all .2s"
                                onmouseover="this.style.background='rgba(59,130,246,0.18)'" onmouseout="this.style.background='rgba(59,130,246,0.08)'">
                                <i class="fas fa-motorcycle fa-2x mb-3" style="color:#3b82f6"></i>
                                <div class="fw-bold" style="color:var(--text-main)">Venta Externa</div>
                                <div class="small mt-1" style="color:var(--text-muted)">Domicilio / Sin caja</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$extraJs = '
<script>
document.addEventListener("DOMContentLoaded", () => {
    const table = document.getElementById("ventasTable");
    if (!table) return;
    const headers = table.querySelectorAll("th.sortable");
    const tbody = table.querySelector("tbody");
    
    // Configuración inicial desde LocalStorage
    let currentConfig = JSON.parse(localStorage.getItem("catnis_ventas_sort")) || { column: 1, order: "desc" };

    const sortTable = (colIndex, order) => {
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const multiplier = order === "asc" ? 1 : -1;

        rows.sort((a, b) => {
            let aVal = a.cells[colIndex].dataset.value || a.cells[colIndex].innerText.trim();
            let bVal = b.cells[colIndex].dataset.value || b.cells[colIndex].innerText.trim();

            // Detectar si es número
            if (!isNaN(aVal) && !isNaN(bVal) && aVal !== "" && bVal !== "") {
                return (parseFloat(aVal) - parseFloat(bVal)) * multiplier;
            }
            
            return aVal.localeCompare(bVal, undefined, {numeric: true, sensitivity: "base"}) * multiplier;
        });

        rows.forEach(row => tbody.appendChild(row));
        
        // Actualizar iconos
        headers.forEach((h, i) => {
            h.classList.remove("sort-asc", "sort-desc");
            if(i == colIndex) h.classList.add("sort-" + order);
        });

        // Guardar preferencia
        localStorage.setItem("catnis_ventas_sort", JSON.stringify({ column: colIndex, order: order }));
    };

    headers.forEach(header => {
        header.addEventListener("click", () => {
            const colIndex = parseInt(header.dataset.column);
            const isCurrent = currentConfig.column === colIndex;
            const newOrder = (isCurrent && currentConfig.order === "desc") ? "asc" : "desc";
            
            currentConfig = { column: colIndex, order: newOrder };
            sortTable(colIndex, newOrder);
        });
    });

    // Aplicar orden guardado
    sortTable(currentConfig.column, currentConfig.order);
});
</script>
';
require_once APP_ROOT . '/views/layout/footer.php';
?>