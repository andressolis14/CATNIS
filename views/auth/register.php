<?php
$pageTitle = 'Gestionar Usuarios';
require_once APP_ROOT . '/views/layout/header.php';
?>

<style>
    /* ==== USUARIOS PAGE - Dark Mode Friendly ==== */
    .usuarios-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
    }

    .usuarios-card .card-header-custom {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .usuarios-card .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        color: var(--text-main);
        font-size: 16px;
    }

    .usuarios-card .card-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .usuarios-card .card-body-custom {
        padding: 24px;
    }

    /* Form Inputs - Dark Mode */
    .input-group-usuarios {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
        border: 1px solid var(--border);
    }

    .input-group-usuarios .input-icon {
        background: var(--bg-card2);
        border: none;
        color: var(--accent);
        padding: 0 14px;
        display: flex;
        align-items: center;
    }

    .input-group-usuarios .form-control,
    .input-group-usuarios .form-select {
        background: var(--bg-card2);
        border: none;
        color: var(--text-main);
        padding: 12px 14px;
        font-size: 14px;
        box-shadow: none;
    }

    .input-group-usuarios .form-control:focus,
    .input-group-usuarios .form-select:focus {
        background: var(--bg-card2);
        color: var(--text-main);
        border-color: transparent;
        box-shadow: none;
        outline: none;
    }

    .input-group-usuarios .form-control::placeholder {
        color: var(--text-dim);
    }

    .input-group-usuarios .form-select option {
        background: var(--bg-card);
        color: var(--text-main);
    }

    /* Submit button */
    .btn-crear-usuario {
        background: linear-gradient(135deg, #4e54c8, #8f94fb);
        border: none;
        color: #fff;
        padding: 13px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        width: 100%;
        cursor: pointer;
        transition: all 0.25s ease;
        letter-spacing: 0.3px;
    }

    .btn-crear-usuario:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(78, 84, 200, 0.4);
    }

    /* Info card */
    .info-tip {
        background: rgba(78, 84, 200, 0.08);
        border: 1px solid rgba(78, 84, 200, 0.2);
        border-radius: 12px;
        padding: 14px 16px;
    }

    .info-tip-icon {
        width: 36px;
        height: 36px;
        background: rgba(78, 84, 200, 0.2);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #8f94fb;
        flex-shrink: 0;
    }

    .tip-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
    }

    .tip-item:last-child {
        border-bottom: none;
    }

    .tip-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 14px;
    }

    .quote-box {
        background: rgba(78, 84, 200, 0.06);
        border-left: 3px solid #8f94fb;
        border-radius: 0 10px 10px 0;
        padding: 12px 16px;
        margin-top: 16px;
    }

    .quote-box p {
        color: var(--text-muted);
        font-size: 13px;
        margin: 0;
        font-style: italic;
    }

    /* Users Table */
    .badge-admin {
        background: rgba(139, 92, 246, 0.2);
        color: #a78bfa;
        font-size: 11px;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
    }

    .badge-usuario {
        background: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
        font-size: 11px;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
    }

    .btn-eliminar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid rgba(239, 68, 68, 0.3);
        background: rgba(239, 68, 68, 0.1);
        color: var(--accent-red, #ef4444);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        font-size: 13px;
    }

    .btn-eliminar:hover {
        background: #ef4444;
        color: #fff;
        border-color: #ef4444;
        transform: scale(1.08);
    }

    .you-badge {
        background: rgba(245, 158, 11, 0.15);
        color: var(--accent, #f59e0b);
        font-size: 10px;
        padding: 2px 7px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .user-avatar-mini {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4e54c8, #8f94fb);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }
</style>

<div class="container-fluid px-4">

    <!-- Header -->
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/dashboard"
                            style="color:var(--accent);">Dashboard</a></li>
                    <li class="breadcrumb-item active" style="color:var(--text-muted);">Usuarios</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0" style="color:var(--text-main);">
                <i class="fas fa-users-cog me-2" style="color:var(--accent-purple, #8b5cf6);"></i>Gestión de Usuarios
            </h2>
        </div>
    </div>

    <!-- Alerts -->
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex align-items-center gap-2 p-3"
                    style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);border-radius:12px;color:#ef4444;">
                    <i class="fas fa-circle-exclamation"></i>
                    <span><?= htmlspecialchars($_SESSION['error']) ?></span>
                </div>
                <?php unset($_SESSION['error']); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['exito'])): ?>
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex align-items-center gap-2 p-3"
                    style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.25);border-radius:12px;color:#10b981;">
                    <i class="fas fa-circle-check"></i>
                    <span><?= htmlspecialchars($_SESSION['exito']) ?></span>
                </div>
                <?php unset($_SESSION['exito']); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Top Row: Formulario + Consejos -->
    <div class="row g-4 mb-4">

        <!-- Formulario Crear -->
        <div class="col-lg-6">
            <div class="usuarios-card h-100">
                <div class="card-header-custom">
                    <div class="card-header-icon" style="background:rgba(78,84,200,0.15);">
                        <i class="fas fa-user-plus" style="color:#8f94fb;"></i>
                    </div>
                    <h5>Crear Nuevo Usuario</h5>
                </div>
                <div class="card-body-custom">
                    <form action="<?= APP_URL ?>/usuarios/guardar" method="POST">
                        <div class="mb-3">
                            <label class="form-label"
                                style="font-size:13px;font-weight:600;color:var(--text-muted);">Nombre Completo</label>
                            <div class="input-group-usuarios d-flex">
                                <span class="input-icon"><i class="fas fa-user"></i></span>
                                <input type="text" name="nombre" class="form-control" placeholder="Ej: Pepito Perez"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"
                                style="font-size:13px;font-weight:600;color:var(--text-muted);">Correo
                                Electrónico</label>
                            <div class="input-group-usuarios d-flex">
                                <span class="input-icon"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="correo" class="form-control" placeholder="correo@ejemplo.com"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"
                                style="font-size:13px;font-weight:600;color:var(--text-muted);">Contraseña
                                Temporal</label>
                            <div class="input-group-usuarios d-flex">
                                <span class="input-icon"><i class="fas fa-lock"></i></span>
                                <input type="password" name="contrasena" class="form-control"
                                    placeholder="Mínimo 6 caracteres" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label"
                                style="font-size:13px;font-weight:600;color:var(--text-muted);">Rol</label>
                            <div class="input-group-usuarios d-flex">
                                <span class="input-icon"><i class="fas fa-shield-alt"></i></span>
                                <select name="rol" class="form-select">
                                    <option value="usuario">Usuario Estándar (Vendedor)</option>
                                    <option value="admin">Administrador (Control Total)</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn-crear-usuario">
                            <i class="fas fa-plus-circle me-2"></i>Registrar Nuevo Usuario
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de Consejos -->
        <div class="col-lg-6">
            <div class="usuarios-card h-100">
                <div class="card-header-custom">
                    <div class="card-header-icon" style="background:rgba(245,158,11,0.15);">
                        <i class="fas fa-lightbulb" style="color:var(--accent);"></i>
                    </div>
                    <h5>Ayuda y Seguridad</h5>
                </div>
                <div class="card-body-custom">
                    <div class="info-tip d-flex align-items-start gap-3 mb-4">
                        <div class="info-tip-icon"><i class="fas fa-lock"></i></div>
                        <div>
                            <h6 class="mb-1 fw-bold" style="color:var(--text-main);font-size:14px;">Registro Privado
                                Activo</h6>
                            <p style="color:var(--text-muted);font-size:13px;margin:0;">El registro público está
                                desactivado. Solo tú como administrador puedes añadir nuevas personas al equipo.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon" style="background:rgba(245,158,11,0.15);color:var(--accent);">
                            <i class="fas fa-key"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold" style="color:var(--text-main);font-size:14px;">Contraseñas
                                Seguras</p>
                            <p class="mb-0" style="color:var(--text-muted);font-size:12px;">Usa una combinación de
                                letras y números.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon"
                            style="background:rgba(16,185,129,0.15);color:var(--accent-green, #10b981);">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold" style="color:var(--text-main);font-size:14px;">Confirmar Correo
                            </p>
                            <p class="mb-0" style="color:var(--text-muted);font-size:12px;">Asegúrate de que el correo
                                electrónico sea real.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon" style="background:rgba(239,68,68,0.15);color:var(--accent-red, #ef4444);">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold" style="color:var(--text-main);font-size:14px;">Eliminar Cuentas
                            </p>
                            <p class="mb-0" style="color:var(--text-muted);font-size:12px;">Puedes eliminar cuentas
                                desde la tabla abajo. No puedes eliminarte a ti mismo.</p>
                        </div>
                    </div>
                    <div class="quote-box">
                        <p>"Gestionar bien a tu equipo es el primer paso para una panadería exitosa."</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="usuarios-card">
                <div class="card-header-custom">
                    <div class="card-header-icon" style="background:rgba(59,130,246,0.15);">
                        <i class="fas fa-users" style="color:var(--accent-blue, #3b82f6);"></i>
                    </div>
                    <h5>Usuarios Registrados</h5>
                    <span class="ms-auto"
                        style="background:rgba(59,130,246,0.15);color:var(--accent-blue, #3b82f6);border-radius:20px;padding:3px 12px;font-size:12px;font-weight:700;">
                        <?= count($usuarios ?? []) ?> usuarios
                    </span>
                </div>
                <div style="overflow-x:auto;">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Usuario</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Registro</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($usuarios)): ?>
                                <?php foreach ($usuarios as $u): ?>
                                    <tr>
                                        <td style="color:var(--text-dim);font-size:13px;"><?= $u['id'] ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar-mini"><?= strtoupper(substr($u['nombre'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-600" style="font-size:14px;color:var(--text-main);">
                                                        <?= htmlspecialchars($u['nombre']) ?>
                                                    </p>
                                                    <?php if ($u['id'] == $_SESSION['usuario_id']): ?>
                                                        <span class="you-badge">Tú</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="color:var(--text-muted);font-size:13px;">
                                            <?= htmlspecialchars($u['correo']) ?>
                                        </td>
                                        <td>
                                            <?php if ($u['rol'] === 'admin'): ?>
                                                <span class="badge-admin"><i class="fas fa-crown me-1"></i>Admin</span>
                                            <?php else: ?>
                                                <span class="badge-usuario"><i class="fas fa-user me-1"></i>Vendedor</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="color:var(--text-dim);font-size:12px;">
                                            <?= isset($u['created_at']) ? date('d/m/Y', strtotime($u['created_at'])) : '—' ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                                                <a href="<?= APP_URL ?>/usuarios/eliminar?id=<?= $u['id'] ?>" class="btn-eliminar"
                                                    title="Eliminar usuario"
                                                    onclick="return confirm('¿Seguro que quieres eliminar a <?= htmlspecialchars(addslashes($u['nombre'])) ?>? Esta acción no se puede deshacer.')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            <?php else: ?>
                                                <span style="color:var(--text-dim);font-size:12px;">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5" style="color:var(--text-dim);">
                                        <i class="fas fa-users fa-2x mb-2 d-block"></i>
                                        No hay usuarios registrados aún.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>