<?php 
// views/periodos/create.php - Crear nuevo período
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-calendar-plus"></i> Crear Nuevo Período</h1>
        <p>Ingresa los datos del nuevo período académico</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/periodos" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Períodos
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-plus-circle"></i> Información del Período</h3>
    </div>
    
    <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/periodos" id="createForm" novalidate>
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-tag"></i> Nombre del Período *
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>"
                           value="<?= isset($data['nombre']) ? htmlspecialchars($data['nombre']) : '' ?>"
                           placeholder="Ej: Primer Semestre 2024"
                           maxlength="100"
                           required>
                    <?php if (isset($errors['nombre'])): ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= $errors['nombre'] ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-help">
                        Ingresa un nombre descriptivo para el período académico
                    </div>
                </div>

                <div class="form-group">
                    <label for="fecha_inicio" class="form-label">
                        <i class="fas fa-calendar-day"></i> Fecha de Inicio *
                    </label>
                    <input type="date" 
                           id="fecha_inicio" 
                           name="fecha_inicio" 
                           class="form-control <?= isset($errors['fecha_inicio']) ? 'is-invalid' : '' ?>"
                           value="<?= isset($data['fecha_inicio']) ? $data['fecha_inicio'] : '' ?>"
                           required>
                    <?php if (isset($errors['fecha_inicio'])): ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= $errors['fecha_inicio'] ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-help">
                        Selecciona la fecha de inicio del período
                    </div>
                </div>

                <div class="form-group">
                    <label for="fecha_fin" class="form-label">
                        <i class="fas fa-calendar-day"></i> Fecha de Finalización *
                    </label>
                    <input type="date" 
                           id="fecha_fin" 
                           name="fecha_fin" 
                           class="form-control <?= isset($errors['fecha_fin']) ? 'is-invalid' : '' ?>"
                           value="<?= isset($data['fecha_fin']) ? $data['fecha_fin'] : '' ?>"
                           required>
                    <?php if (isset($errors['fecha_fin'])): ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= $errors['fecha_fin'] ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-help">
                        Selecciona la fecha de finalización del período
                    </div>
                </div>

                <div class="form-group full-width">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" 
                                   id="activo" 
                                   name="activo" 
                                   value="1"
                                   <?= (isset($data['activo']) && $data['activo']) ? 'checked' : '' ?>>
                            <span class="checkbox-custom"></span>
                            <span class="checkbox-text">
                                <i class="fas fa-check-circle"></i>
                                Período activo
                            </span>
                        </label>
                        <div class="form-help">
                            Marca esta casilla si el período debe estar activo inmediatamente
                        </div>
                    </div>
                </div>
            </div>

            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <?php if (is_array($error)): ?>
                                <?php foreach ($error as $subError): ?>
                                    <li><?= htmlspecialchars($subError) ?></li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="duration-preview" id="durationPreview" style="display: none;">
                <div class="preview-card">
                    <h4><i class="fas fa-clock"></i> Vista Previa</h4>
                    <p><strong>Duración del período:</strong> <span id="durationText">0 días</span></p>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?php echo BASE_URL; ?>/periodos" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="reset" class="btn btn-outline">
                    <i class="fas fa-redo"></i> Limpiar
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> Crear Período
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validación del formulario
document.getElementById('createForm').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value.trim();
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    
    let hasErrors = false;
    
    // Limpiar errores previos
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    
    // Validar nombre
    if (!nombre) {
        showFieldError('nombre', 'El nombre es requerido');
        hasErrors = true;
    }
    
    // Validar fechas
    if (!fechaInicio) {
        showFieldError('fecha_inicio', 'La fecha de inicio es requerida');
        hasErrors = true;
    }
    
    if (!fechaFin) {
        showFieldError('fecha_fin', 'La fecha de fin es requerida');
        hasErrors = true;
    }
    
    // Validar que la fecha de inicio sea anterior a la de fin
    if (fechaInicio && fechaFin && fechaInicio >= fechaFin) {
        showFieldError('fecha_fin', 'La fecha de fin debe ser posterior a la fecha de inicio');
        hasErrors = true;
    }
    
    if (hasErrors) {
        e.preventDefault();
        document.querySelector('.form-control.is-invalid').focus();
    }
});

// Función para mostrar errores de campo
function showFieldError(fieldName, message) {
    const field = document.getElementById(fieldName);
    field.classList.add('is-invalid');
    
    const feedback = document.createElement('div');
    feedback.className = 'invalid-feedback';
    feedback.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + message;
    
    field.parentNode.appendChild(feedback);
}

// Calcular y mostrar duración del período
function updateDurationPreview() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const preview = document.getElementById('durationPreview');
    const durationText = document.getElementById('durationText');
    
    if (fechaInicio && fechaFin) {
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);
        const diferencia = fin - inicio;
        const dias = Math.ceil(diferencia / (1000 * 60 * 60 * 24));
        
        if (dias > 0) {
            durationText.textContent = dias + ' días (' + Math.ceil(dias / 7) + ' semanas aprox.)';
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    } else {
        preview.style.display = 'none';
    }
}

// Escuchar cambios en las fechas
document.getElementById('fecha_inicio').addEventListener('change', updateDurationPreview);
document.getElementById('fecha_fin').addEventListener('change', updateDurationPreview);

// Cuando cambie la fecha de inicio, actualizar la fecha mínima de fin
document.getElementById('fecha_inicio').addEventListener('change', function() {
    const fechaInicio = this.value;
    if (fechaInicio) {
        const minFin = new Date(fechaInicio);
        minFin.setDate(minFin.getDate() + 1);
        document.getElementById('fecha_fin').min = minFin.toISOString().split('T')[0];
    }
});
</script>

<?php require_once 'views/layout/footer.php'; ?>