<?php 
// views/periodos/show.php - Ver detalles completos del período
require_once 'views/layout/header.php'; 

// Calcular información adicional
$inicio = new DateTime($periodo['fecha_inicio']);
$fin = new DateTime($periodo['fecha_fin']);
$hoy = new DateTime();
$duracion = $inicio->diff($fin)->days > 0 ? $inicio->diff($fin)->days : 1; // Evitar división por cero

// Determinar estado del período
$estado_periodo = 'pendiente';
if ($hoy < $inicio) {
    $estado_periodo = 'pendiente';
} elseif ($hoy >= $inicio && $hoy <= $fin) {
    $estado_periodo = 'en_curso';
} else {
    $estado_periodo = 'finalizado';
}
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-eye"></i> Detalles del Período</h1>
        <p>Información completa del período académico</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/periodos" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Períodos
        </a>
        <a href="<?php echo BASE_URL; ?>/periodos/editar/<?= $periodo['id'] ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($periodo['nombre']) ?></h3>
        <div class="period-status">
            <?php if ($periodo['activo']): ?>
                <span class="badge badge-success">
                    <i class="fas fa-check-circle"></i> Activo
                </span>
            <?php else: ?>
                <span class="badge badge-secondary">
                    <i class="fas fa-pause-circle"></i> Inactivo
                </span>
            <?php endif; ?>
            
            <?php if ($estado_periodo === 'pendiente'): ?>
                <span class="badge badge-info">
                    <i class="fas fa-clock"></i> Por Iniciar
                </span>
            <?php elseif ($estado_periodo === 'en_curso'): ?>
                <span class="badge badge-warning">
                    <i class="fas fa-play-circle"></i> En Curso
                </span>
            <?php else: ?>
                <span class="badge badge-dark">
                    <i class="fas fa-stop-circle"></i> Finalizado
                </span>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-section">
                <h4><i class="fas fa-info-circle"></i> Información General</h4>
                <div class="detail-items">
                    <div class="detail-item">
                        <strong>Nombre:</strong>
                        <span><?= htmlspecialchars($periodo['nombre']) ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>ID:</strong>
                        <span>#<?= $periodo['id'] ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Estado:</strong>
                        <?php if ($periodo['activo']): ?>
                            <span class="status-active">
                                <i class="fas fa-check-circle"></i> Activo
                            </span>
                        <?php else: ?>
                            <span class="status-inactive">
                                <i class="fas fa-pause-circle"></i> Inactivo
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="detail-item">
                        <strong>Progreso:</strong>
                        <span class="period-progress">
                            <?php if ($estado_periodo === 'pendiente'): ?>
                                <i class="fas fa-hourglass-start"></i> Aún no iniciado
                            <?php elseif ($estado_periodo === 'en_curso'): ?>
                                <?php 
                                    $dias_transcurridos = $inicio->diff($hoy)->days;
                                    $porcentaje = round(($dias_transcurridos / $duracion) * 100, 1);
                                ?>
                                <i class="fas fa-running"></i> <?= $porcentaje ?>% completado
                            <?php else: ?>
                                <i class="fas fa-flag-checkered"></i> Finalizado
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h4><i class="fas fa-calendar"></i> Fechas</h4>
                <div class="detail-items">
                    <div class="detail-item">
                        <strong>Fecha de Inicio:</strong>
                        <span class="date-value">
                            <i class="fas fa-calendar-day"></i>
                            <?= $inicio->format('d/m/Y') ?>
                            <small>(<?= $inicio->format('l, j \d\e F \d\e Y') ?>)</small>
                        </span>
                    </div>
                    <div class="detail-item">
                        <strong>Fecha de Fin:</strong>
                        <span class="date-value">
                            <i class="fas fa-calendar-day"></i>
                            <?= $fin->format('d/m/Y') ?>
                            <small>(<?= $fin->format('l, j \d\e F \d\e Y') ?>)</small>
                        </span>
                    </div>
                    <div class="detail-item">
                        <strong>Duración:</strong>
                        <span class="duration-value">
                            <i class="fas fa-clock"></i>
                            <?= $duracion ?> días
                            <small>(<?= ceil($duracion / 7) ?> semanas aprox.)</small>
                        </span>
                    </div>
                    <div class="detail-item">
                        <strong>Días Restantes:</strong>
                        <span class="remaining-days">
                            <?php if ($estado_periodo === 'pendiente'): ?>
                                <?php $dias_para_inicio = $hoy->diff($inicio)->days; ?>
                                <i class="fas fa-hourglass-half"></i>
                                <?= $dias_para_inicio ?> días para iniciar
                            <?php elseif ($estado_periodo === 'en_curso'): ?>
                                <?php $dias_restantes = $hoy->diff($fin)->days; ?>
                                <i class="fas fa-hourglass-end"></i>
                                <?= $dias_restantes ?> días restantes
                            <?php else: ?>
                                <i class="fas fa-check"></i>
                                Período finalizado
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h4><i class="fas fa-cogs"></i> Información del Sistema</h4>
                <div class="detail-items">
                    <div class="detail-item">
                        <strong>Fecha de Creación:</strong>
                        <span><?= date('d/m/Y H:i:s', strtotime($periodo['created_at'])) ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Última Actualización:</strong>
                        <span><?= date('d/m/Y H:i:s', strtotime($periodo['updated_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="timeline-section">
            <h4><i class="fas fa-chart-line"></i> Línea de Tiempo</h4>
            <div class="timeline-container">
                <div class="timeline-bar">
                    <?php if ($estado_periodo === 'en_curso'): ?>
                        <?php 
                            $dias_transcurridos = $inicio->diff($hoy)->days;
                            $porcentaje_completado = ($dias_transcurridos / $duracion) * 100;
                        ?>
                        <div class="timeline-progress" style="width: <?= min($porcentaje_completado, 100) ?>%"></div>
                    <?php elseif ($estado_periodo === 'finalizado'): ?>
                        <div class="timeline-progress completed" style="width: 100%"></div>
                    <?php endif; ?>
                </div>
                <div class="timeline-labels">
                    <span class="timeline-start">
                        <i class="fas fa-play"></i>
                        <?= $inicio->format('d/m') ?>
                    </span>
                    <?php if ($estado_periodo === 'en_curso'): ?>
                        <span class="timeline-current" style="left: <?= isset($porcentaje_completado) ? min($porcentaje_completado, 100) : 0 ?>%;">
                            <i class="fas fa-map-marker-alt"></i>
                            Hoy
                        </span>
                    <?php endif; ?>
                    <span class="timeline-end">
                        <i class="fas fa-stop"></i>
                        <?= $fin->format('d/m') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-tools"></i> Acciones</h3>
    </div>
    <div class="card-body">
        <div class="action-grid">
            <a href="<?php echo BASE_URL; ?>/periodos/editar/<?= $periodo['id'] ?>" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="action-content">
                    <h5>Editar Período</h5>
                    <p>Modificar información del período</p>
                </div>
            </a>
            
            <a href="<?php echo BASE_URL; ?>/grupos?periodo=<?= $periodo['id'] ?>" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="action-content">
                    <h5>Ver Grupos</h5>
                    <p>Grupos asociados a este período</p>
                </div>
            </a>
            
            <?php if ($periodo['activo']): ?>
                <button onclick="desactivarPeriodo()" class="action-card clickable">
                    <div class="action-icon warning">
                        <i class="fas fa-pause"></i>
                    </div>
                    <div class="action-content">
                        <h5>Desactivar</h5>
                        <p>Desactivar este período</p>
                    </div>
                </button>
            <?php else: ?>
                <button onclick="activarPeriodo()" class="action-card clickable">
                    <div class="action-icon success">
                        <i class="fas fa-play"></i>
                    </div>
                    <div class="action-content">
                        <h5>Activar</h5>
                        <p>Activar este período</p>
                    </div>
                </button>
            <?php endif; ?>
            
            <button onclick="confirmarEliminacion()" class="action-card clickable danger">
                <div class="action-icon">
                    <i class="fas fa-trash"></i>
                </div>
                <div class="action-content">
                    <h5>Eliminar</h5>
                    <p>Eliminar permanentemente</p>
                </div>
            </button>
        </div>
    </div>
</div>

<style>
.period-status {
    display: flex;
    gap: 10px;
    align-items: center;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.detail-section {
    background: #f8f9fa;
    border: 1px solid var(--border);
    border-radius: var(--border-radius);
    padding: 25px;
}

.detail-section h4 {
    color: var(--dark);
    margin-bottom: 20px;
    font-size: 1.1rem;
    border-bottom: 2px solid var(--primary);
    padding-bottom: 8px;
}

.detail-section h4 i {
    color: var(--primary);
    margin-right: 10px;
}

.detail-items {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item strong {
    color: var(--dark);
    font-size: 0.9rem;
    min-width: 40%;
}

.detail-item span {
    color: var(--secondary);
    text-align: right;
    flex: 1;
}

.date-value, .duration-value {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 2px;
}

.date-value small, .duration-value small {
    font-size: 0.75rem;
    color: var(--secondary);
    opacity: 0.8;
}

.status-active {
    color: var(--success);
}

.status-inactive {
    color: var(--secondary);
}

.timeline-section {
    background: var(--white);
    border: 2px solid var(--primary);
    border-radius: var(--border-radius);
    padding: 25px;
    margin-top: 20px;
}

.timeline-section h4 {
    color: var(--primary);
    margin-bottom: 20px;
    font-size: 1.1rem;
}

.timeline-container {
    position: relative;
}

.timeline-bar {
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    position: relative;
    margin-bottom: 15px;
}

.timeline-progress {
    height: 100%;
    background: linear-gradient(90deg, var(--primary), var(--success));
    border-radius: 4px;
    transition: width 0.3s ease;
    position: relative;
}

.timeline-progress.completed {
    background: var(--success);
}

.timeline-labels {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.timeline-start, .timeline-end, .timeline-current {
    font-size: 0.85rem;
    color: var(--secondary);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.timeline-current {
    position: absolute;
    transform: translateX(-50%);
    color: var(--warning);
    font-weight: 600;
}

.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.action-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    border: 2px solid var(--border);
    border-radius: var(--border-radius);
    text-decoration: none;
    color: var(--dark);
    transition: var(--transition);
    background: var(--white);
}

.action-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px var(--shadow);
    color: var(--dark);
    text-decoration: none;
}

.action-card.clickable {
    cursor: pointer;
    border: none;
    background: var(--white);
}

.action-card.danger:hover {
    border-color: var(--danger);
    background: rgba(231, 76, 60, 0.02);
}

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary);
    color: var(--white);
    font-size: 1.2rem;
    flex-shrink: 0;
}

.action-icon.warning {
    background: var(--warning);
}

.action-icon.success {
    background: var(--success);
}

.action-card.danger .action-icon {
    background: var(--danger);
}

.action-content h5 {
    margin: 0 0 5px 0;
    color: var(--dark);
    font-size: 1rem;
}

.action-content p {
    margin: 0;
    color: var(--secondary);
    font-size: 0.85rem;
}
</style>

<script>
function confirmarEliminacion() {
    if (confirm('¿Estás seguro de que deseas eliminar este período? Esta acción no se puede deshacer.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo BASE_URL; ?>/periodos/eliminar/<?= $periodo['id'] ?>';
        document.body.appendChild(form);
        form.submit();
    }
}

function activarPeriodo() {
    alert('Funcionalidad de activación pendiente de implementar.');
}

function desactivarPeriodo() {
    alert('Funcionalidad de desactivación pendiente de implementar.');
}

// Actualizar etiqueta de posición actual en la línea de tiempo
document.addEventListener('DOMContentLoaded', function() {
    const timelineCurrent = document.querySelector('.timeline-current');
    if (timelineCurrent) {
        timelineCurrent.style.animation = 'pulse 2s infinite';
    }
});

// Agregar animación CSS para el pulso
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
`;
document.head.appendChild(style);
</script>

<?php require_once 'views/layout/footer.php'; ?>