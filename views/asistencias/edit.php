<?php 
// views/asistencias/edit.php - Formulario para editar un registro de asistencia
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-edit"></i> Editar Asistencia</h1>
        <p>Modifica el registro de asistencia para un estudiante en una fecha específica.</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/asistencias" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al Historial</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-clock"></i> Modificando Registro</h3>
    </div>
    
    <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/asistencias/actualizar/<?= $asistencia['id'] ?>" novalidate>
            <div class="info-section">
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Estudiante:</strong>
                        <span><?= htmlspecialchars($asistencia['estudiante_nombre'] . ' ' . $asistencia['estudiante_apellido']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Materia:</strong>
                        <span><?= htmlspecialchars($asistencia['materia_nombre']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Grupo:</strong>
                        <span><?= htmlspecialchars($asistencia['grupo_nombre']) ?></span>
                    </div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="fecha" class="form-label"><i class="fas fa-calendar-alt"></i> Fecha de Asistencia *</label>
                    <input type="date" id="fecha" name="fecha" class="form-control" value="<?= htmlspecialchars($asistencia['fecha']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="estado" class="form-label"><i class="fas fa-tasks"></i> Estado *</label>
                    <select id="estado" name="estado" class="form-control" required>
                        <option value="presente" <?= $asistencia['estado'] == 'presente' ? 'selected' : '' ?>>Presente</option>
                        <option value="ausente" <?= $asistencia['estado'] == 'ausente' ? 'selected' : '' ?>>Ausente</option>
                        <option value="tardanza" <?= $asistencia['estado'] == 'tardanza' ? 'selected' : '' ?>>Tardanza</option>
                        <option value="justificado" <?= $asistencia['estado'] == 'justificado' ? 'selected' : '' ?>>Justificado</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?php echo BASE_URL; ?>/asistencias" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>