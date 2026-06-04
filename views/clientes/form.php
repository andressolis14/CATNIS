<?php
$editando  = isset($cliente);
$pageTitle = $editando ? 'Editar Cliente' : 'Nuevo Cliente';
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="page-header">
    <h4><i class="fas fa-<?= $editando ? 'pen' : 'user-plus' ?> me-2" style="color:var(--accent)"></i><?= $pageTitle ?></h4>
    <p><a href="<?= APP_URL ?>/clientes" style="color:var(--accent);text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Volver a clientes</a></p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="form-card">
            <form method="POST" action="<?= $editando ? APP_URL.'/clientes/editar?id='.$cliente['id'] : APP_URL.'/clientes/crear' ?>">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($cliente['nombre'] ?? '') ?>" placeholder="Nombre completo" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($cliente['telefono'] ?? '') ?>" placeholder="Ej: 555-1234">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($cliente['correo'] ?? '') ?>" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Dirección</label>
                        <textarea name="direccion" class="form-control" rows="2" placeholder="Dirección opcional..."><?= htmlspecialchars($cliente['direccion'] ?? '') ?></textarea>
                    </div>

                    <!-- Datos de la mascota -->
                    <div class="col-12 mt-3 mb-1">
                        <h6 style="color:var(--accent); font-size:14px; border-bottom:1px solid var(--border); padding-bottom:5px;">
                            <i class="fas fa-paw me-2"></i>Datos de la Mascota (Opcional)
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre de la Mascota</label>
                        <input type="text" name="nombre_mascota" class="form-control" value="<?= htmlspecialchars($cliente['nombre_mascota'] ?? '') ?>" placeholder="Ej: Max, Luna...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cumpleaños Mascota</label>
                        <input type="date" name="cumpleanos_mascota" class="form-control" value="<?= htmlspecialchars($cliente['cumpleanos_mascota'] ?? '') ?>">
                    </div>

                    <div class="col-12 mt-2">
                        <button type="submit" class="btn-primary-custom me-2">
                            <i class="fas fa-<?= $editando ? 'floppy-disk' : 'plus' ?> me-2"></i>
                            <?= $editando ? 'Actualizar' : 'Crear Cliente' ?>
                        </button>
                        <a href="<?= APP_URL ?>/clientes" style="color:var(--text-muted);text-decoration:none;font-size:14px;">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
