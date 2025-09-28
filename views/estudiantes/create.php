<?php 
// views/estudiantes/create.php - Formulario para crear un nuevo estudiante
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-user-plus"></i> Registrar Nuevo Estudiante</h1>
        <p>Ingresa los datos personales del estudiante</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/estudiantes" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-id-card"></i> Información del Estudiante</h3>
    </div>
    
    <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/estudiantes" id="createEstudianteForm" novalidate>
            <div class="form-grid">
                <div class="form-group">
                    <label for="registro" class="form-label"><i class="fas fa-hashtag"></i> Número de Registro *</label>
                    <input type="text" id="registro" name="registro" class="form-control <?= isset($errors['registro']) ? 'is-invalid' : '' ?>" value="<?= isset($data['registro']) ? htmlspecialchars($data['registro']) : '' ?>" placeholder="Ej: 2024-00123" required>
                    <?php if (isset($errors['registro'])): ?><div class="invalid-feedback"><?= $errors['registro'] ?></div><?php endif; ?>
                    <div class="form-help">Código único de matrícula del estudiante.</div>
                </div>

                <div class="form-group">
                    <label for="nombre" class="form-label"><i class="fas fa-user"></i> Nombres *</label>
                    <input type="text" id="nombre" name="nombre" class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" value="<?= isset($data['nombre']) ? htmlspecialchars($data['nombre']) : '' ?>" placeholder="Ej: Juan Carlos" required>
                    <?php if (isset($errors['nombre'])): ?><div class="invalid-feedback"><?= $errors['nombre'] ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="apellido" class="form-label"><i class="fas fa-user"></i> Apellidos *</label>
                    <input type="text" id="apellido" name="apellido" class="form-control <?= isset($errors['apellido']) ? 'is-invalid' : '' ?>" value="<?= isset($data['apellido']) ? htmlspecialchars($data['apellido']) : '' ?>" placeholder="Ej: Pérez González" required>
                    <?php if (isset($errors['apellido'])): ?><div class="invalid-feedback"><?= $errors['apellido'] ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="correo" class="form-label"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" class="form-control <?= isset($errors['correo']) ? 'is-invalid' : '' ?>" value="<?= isset($data['correo']) ? htmlspecialchars($data['correo']) : '' ?>" placeholder="Ej: juan.perez@email.com">
                    <?php if (isset($errors['correo'])): ?><div class="invalid-feedback"><?= $errors['correo'] ?></div><?php endif; ?>
                    <div class="form-help">Opcional. Se usará para notificaciones.</div>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?php echo BASE_URL; ?>/estudiantes" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                <button type="reset" class="btn btn-outline"><i class="fas fa-redo"></i> Limpiar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Registrar Estudiante</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>