<?php 
// views/inscripciones/create.php - Formulario para crear una nueva inscripción
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-user-plus"></i> Realizar Nueva Inscripción</h1>
        <p>Selecciona un estudiante y el grupo al que deseas inscribirlo</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/inscripciones" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-edit"></i> Formulario de Inscripción</h3>
    </div>
    
    <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/inscripciones" id="createInscripcionForm" novalidate>
            
            <?php if (isset($errors['duplicado'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= $errors['duplicado'] ?>
                </div>
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label for="estudiante_id" class="form-label"><i class="fas fa-user-graduate"></i> Estudiante *</label>
                    <select id="estudiante_id" name="estudiante_id" class="form-control <?= isset($errors['estudiante_id']) ? 'is-invalid' : '' ?>" required>
                        <option value="">-- Selecciona un estudiante --</option>
                        <?php if (empty($estudiantes)): ?>
                            <option disabled>No hay estudiantes registrados</option>
                        <?php else: ?>
                            <?php foreach ($estudiantes as $estudiante): ?>
                                <option value="<?= $estudiante['id'] ?>" <?= (isset($data['estudiante_id']) && $data['estudiante_id'] == $estudiante['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?> (Reg: <?= htmlspecialchars($estudiante['registro']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($errors['estudiante_id'])): ?><div class="invalid-feedback"><?= $errors['estudiante_id'] ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="grupo_id" class="form-label"><i class="fas fa-layer-group"></i> Grupo *</label>
                    <select id="grupo_id" name="grupo_id" class="form-control <?= isset($errors['grupo_id']) ? 'is-invalid' : '' ?>" required>
                        <option value="">-- Selecciona un grupo --</option>
                        <?php if (empty($grupos)): ?>
                            <option disabled>No hay grupos creados</option>
                        <?php else: ?>
                            <?php foreach ($grupos as $grupo): ?>
                                <option value="<?= $grupo['id'] ?>" <?= (isset($data['grupo_id']) && $data['grupo_id'] == $grupo['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($grupo['nombre']) ?> - <?= htmlspecialchars($grupo['materia_nombre']) ?> (<?= htmlspecialchars($grupo['periodo_nombre']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($errors['grupo_id'])): ?><div class="invalid-feedback"><?= $errors['grupo_id'] ?></div><?php endif; ?>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?php echo BASE_URL; ?>/inscripciones" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Inscribir Estudiante</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>