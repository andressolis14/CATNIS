<?php
// ============================================================
// VISTA: Catálogo Maestro de Ítems de Gastos
// ============================================================
$pageTitle = 'Catálogo de Ítems';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h4><i class="fas fa-boxes me-2" style="color:var(--accent)"></i>Catálogo Maestro de Ítems</h4>
        <p>Gestiona productos y servicios para registrar tus gastos</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalCrearItem">
            <i class="fas fa-plus me-2"></i>Nuevo Ítem
        </button>
        <a href="<?= APP_URL ?>/gastos" class="btn"
            style="background:rgba(255,255,255,0.03); color:var(--text-muted); border:1px solid var(--border); border-radius:10px; padding:8px 16px; font-weight:600; display:flex; align-items:center; gap:8px; text-decoration:none;">
            <i class="fas fa-arrow-left"></i> <span>Volver a Gastos</span>
        </a>
    </div>
</div>

<div class="table-card p-4 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-dark border-0 text-muted"><i class="fas fa-search"></i></span>
                <input type="text" id="busquedaItems" class="form-control" placeholder="Buscar por nombre o código..." onkeyup="filtrarItems()">
            </div>
        </div>
        <div class="col-md-6 text-end">
            <span class="text-muted small">Total de ítems: <b style="color:var(--accent)"><?= count($items) ?></b></span>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-dark-custom" id="tablaMaestro">
            <thead>
                <tr>
                    <th style="width:150px;">Código</th>
                    <th>Nombre del Ítem</th>
                    <th style="width:120px;">Unidad</th>
                    <th style="width:180px;">Fecha Registro</th>
                    <th style="width:100px;" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($items)): ?>
                    <tr><td colspan="5" class="text-center py-5 text-muted">No hay ítems registrados aún. Crea uno nuevo para empezar.</td></tr>
                <?php else: ?>
                    <?php foreach($items as $it): ?>
                        <tr>
                            <td class="text-accent fw-600"><?= $it['codigo'] ?></td>
                            <td class="text-main fw-500"><?= htmlspecialchars($it['nombre']) ?></td>
                            <td>
                                <span class="badge" style="background:rgba(59,130,246,0.1); color:var(--accent-blue); border:1px solid rgba(59,130,246,0.2);">
                                    <?= htmlspecialchars($it['unidad_medida'] ?? 'unid') ?>
                                </span>
                            </td>
                            <td class="text-muted small"><?= !empty($it['fecha_creacion']) ? date('d/m/Y H:i', strtotime($it['fecha_creacion'])) : '—' ?></td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn-sm-icon" title="Editar" 
                                            style="border-color:rgba(245,158,11,0.3); background:rgba(245,158,11,0.1); color:var(--accent);"
                                            onclick="abrirModalEdicion(<?= htmlspecialchars(json_encode($it)) ?>)">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button class="btn-delete-sm" title="Eliminar" 
                                            onclick="if(confirm('¿Seguro que deseas eliminar este ítem? Solo funcionará si no ha sido usado en gastos.')) window.location.href='<?= APP_URL ?>/gastos/eliminarItem?id=<?= $it['id'] ?>'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Creación -->
<div class="modal fade" id="modalCrearItem" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-accent"><i class="fas fa-plus me-2"></i>Nuevo Ítem de Gasto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= APP_URL ?>/gastos/crearItem" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Nombre del Ítem</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Harina, Luz, Transporte..." required>
                        <div class="form-text mt-1" style="font-size:10px;">El código se generará automáticamente.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Unidad de Medida</label>
                        <select name="unidad_medida" class="form-select">
                            <option value="unid">Unidad (unid)</option>
                            <option value="kg">Kilogramos (kg)</option>
                            <option value="gr">Gramos (gr)</option>
                            <option value="lt">Litros (lt)</option>
                            <option value="ml">Mililitros (ml)</option>
                            <option value="m">Metros (m)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-custom">Crear Ítem</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Edición -->
<div class="modal fade" id="modalEditarItem" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-accent"><i class="fas fa-edit me-2"></i>Editar Ítem</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= APP_URL ?>/gastos/editarItem" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label text-muted">Código</label>
                        <input type="text" name="codigo" id="edit_codigo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Nombre del Ítem</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Unidad de Medida</label>
                        <select name="unidad_medida" id="edit_unidad" class="form-select">
                            <option value="unid">Unidad (unid)</option>
                            <option value="kg">Kilogramos (kg)</option>
                            <option value="gr">Gramos (gr)</option>
                            <option value="lt">Litros (lt)</option>
                            <option value="ml">Mililitros (ml)</option>
                            <option value="m">Metros (m)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-custom">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalEdicion(item) {
    document.getElementById('edit_id').value = item.id;
    document.getElementById('edit_codigo').value = item.codigo;
    document.getElementById('edit_nombre').value = item.nombre;
    document.getElementById('edit_unidad').value = item.unidad_medida || 'unid';
    new bootstrap.Modal(document.getElementById('modalEditarItem')).show();
}
function filtrarItems() {
    const input = document.getElementById('busquedaItems');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('tablaMaestro');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const tdCod = tr[i].getElementsByTagName('td')[0];
        const tdNom = tr[i].getElementsByTagName('td')[1];
        if (tdCod || tdNom) {
            const txtCod = tdCod.textContent || tdCod.innerText;
            const txtNom = tdNom.textContent || tdNom.innerText;
            if (txtCod.toUpperCase().indexOf(filter) > -1 || txtNom.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
