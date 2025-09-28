<?php 
// views/periodos/edit.php - Editar período existente
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-edit"></i> Editar Período</h1>
        <p>Modifica los datos del período académico</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/periodos" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Períodos
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-edit"></i> Editar: <?= htmlspecialchars($periodo['nombre']) ?></h3>
    </div>
    
    <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/periodos/actualizar/<?= $periodo['id'] ?>" id="editForm" novalidate>
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-tag"></i> Nombre del Período *
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($periodo['nombre']) ?>"
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
                           value="<?= $periodo['fecha_inicio'] ?>"
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
                           value="<?= $periodo['fecha_fin'] ?>"
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
                                   <?= $periodo['activo'] ? 'checked' : '' ?>>
                            <span class="checkbox-custom"></span>
                            <span class="checkbox-text">
                                <i class="fas fa-check-circle"></i>
                                Período activo
                            </span>
                        </label>
                        <div class="form-help">
                            Marca esta casilla si el período debe estar activo
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-section">
                <h4><i class="fas fa-info-circle"></i> Información del Período</h4>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Fecha de Creación:</strong>
                        <span><?= date('d/m/Y H:i', strtotime($periodo['created_at'])) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Última Actualización:</strong>
                        <span><?= date('d/m/Y H:i', strtotime($periodo['updated_at'])) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Duración Actual:</strong>
                        <span id="currentDuration">
                            <?php 
                                $inicio = new DateTime($periodo['fecha_inicio']);
                                $fin = new DateTime($periodo['fecha_fin']);
                                $duracion = $inicio->diff($fin)->days;
                                echo $duracion . ' días';
                            ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <strong>Estado:</strong>
                        <?php if ($periodo['activo']): ?>
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i> Activo
                            </span>
                        <?php else: ?>
                            <span class="badge badge-secondary">
                                <i class="fas fa-pause-circle"></i> Inactivo
                            </span>
                        <?php endif; ?>
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
                    <h4><i class="fas fa-clock"></i> Nueva Duración</h4>
                    <p><strong>Duración actualizada:</strong> <span id="durationText">0 días</span></p>
                    <p class="duration-change" id="durationChange"></p>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?php echo BASE_URL; ?>/periodos" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="button" onclick="resetForm()" class="btn btn-outline">
                    <i class="fas fa-undo"></i> Restaurar
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> Actualizar Período
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.info-section {
    background: #f8f9fa;
    border: 1px solid var(--border);
    border-radius: var(--border-radius);
    padding: 25px;
    margin: 25px 0;
}

.info-section h4 {
    color: var(--dark);
    margin-bottom: 20px;
    font-size: 1.1rem;
}

.info-section h4 i {
    color: var(--info);
    margin-right: 10px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item strong {
    color: var(--dark);
    font-size: 0.9rem;
}

.info-item span {
    color: var(--secondary);
    font-size: 0.95rem;
}

.duration-change {
    font-size: 0.9rem;
    margin-top: 10px;
    padding: 10px;
    border-radius: 5px;
}

.duration-change.increase {
    background: rgba(243, 156, 18, 0.1);
    color: var(--warning);
}

.duration-change.decrease {
    background: rgba(39, 174, 96, 0.1);
    color: var(--success);
}

.duration-change.same {
    background: rgba(108, 117, 125, 0.1);
    color: var(--secondary);
}
</style>

<script>
// Datos originales del período
const originalData = {
    nombre: <?= json_encode($periodo['nombre']) ?>,
    fecha_inicio: <?= json_encode($periodo['fecha_inicio']) ?>,
    fecha_fin: <?= json_encode($periodo['fecha_fin']) ?>,
    activo: <?= $periodo['activo'] ? 'true' : 'false' ?>,
    duracion: <?= (new DateTime($periodo['fecha_inicio']))->diff(new DateTime($periodo['fecha_fin']))->days ?>
};

// Validación del formulario
document.getElementById('editForm').addEventListener('submit', function(e) {
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

// Calcular y mostrar cambios en duración
function updateDurationPreview() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const preview = document.getElementById('durationPreview');
    const durationText = document.getElementById('durationText');
    const durationChange = document.getElementById('durationChange');
    
    if (fechaInicio && fechaFin) {
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);
        const diferencia = fin - inicio;
        const dias = Math.ceil(diferencia / (1000 * 60 * 60 * 24));
        
        if (dias > 0) {
            durationText.textContent = dias + ' días (' + Math.ceil(dias / 7) + ' semanas aprox.)';
            
            // Mostrar cambio respecto al original
            const cambio = dias - originalData.duracion;
            let changeText = '';
            let changeClass = '';
            
            if (cambio > 0) {
                changeText = `+${cambio} días más que el período original`;
                changeClass = 'increase';
            } else if (cambio < 0) {
                changeText = `${Math.abs(cambio)} días menos que el período original`;
                changeClass = 'decrease';
            } else {
                changeText = 'Sin cambios en la duración';
                changeClass = 'same';
            }
            
            durationChange.textContent = changeText;
            durationChange.className = `duration-change ${changeClass}`;
            
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    } else {
        preview.style.display = 'none';
    }
}

// Función para restaurar valores originales
function resetForm() {
    document.getElementById('nombre').value = originalData.nombre;
    document.getElementById('fecha_inicio').value = originalData.fecha_inicio;
    document.getElementById('fecha_fin').value = originalData.fecha_fin;
    document.getElementById('activo').checked = originalData.activo;
    
    // Limpiar errores
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    
    // Ocultar preview
    document.getElementById('durationPreview').style.display = 'none';
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

// Mostrar preview inicial si es necesario
document.addEventListener('DOMContentLoaded', function() {
    updateDurationPreview();
});
</script>

<?php require_once 'views/layout/footer.php'; ?>