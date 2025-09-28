<?php 
// views/estudiantes/edit.php - Formulario para editar un estudiante
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-user-edit"></i> Editar Estudiante</h1>
        <p>Modifica los datos personales del estudiante</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/estudiantes" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-id-card"></i> Editando a: <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></h3>
    </div>
    
    <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/estudiantes/actualizar/<?= $estudiante['id'] ?>" id="editEstudianteForm" novalidate>
            <div class="form-grid">
                <div class="form-group">
                    <label for="registro" class="form-label"><i class="fas fa-hashtag"></i> Número de Registro *</label>
                    <input type="text" id="registro" name="registro" class="form-control <?= isset($errors['registro']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($estudiante['registro']) ?>" required>
                    <?php if (isset($errors['registro'])): ?><div class="invalid-feedback"><?= $errors['registro'] ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="nombre" class="form-label"><i class="fas fa-user"></i> Nombres *</label>
                    <input type="text" id="nombre" name="nombre" class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($estudiante['nombre']) ?>" required>
                    <?php if (isset($errors['nombre'])): ?><div class="invalid-feedback"><?= $errors['nombre'] ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="apellido" class="form-label"><i class="fas fa-user"></i> Apellidos *</label>
                    <input type="text" id="apellido" name="apellido" class="form-control <?= isset($errors['apellido']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($estudiante['apellido']) ?>" required>
                    <?php if (isset($errors['apellido'])): ?><div class="invalid-feedback"><?= $errors['apellido'] ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="correo" class="form-label"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" class="form-control <?= isset($errors['correo']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($estudiante['correo']) ?>">
                    <?php if (isset($errors['correo'])): ?><div class="invalid-feedback"><?= $errors['correo'] ?></div><?php endif; ?>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?php echo BASE_URL; ?>/estudiantes" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar Estudiante</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>