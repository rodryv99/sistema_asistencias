<?php 
// views/estudiantes/show.php - Vista de detalles de un estudiante
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-user-graduate"></i> Detalles del Estudiante</h1>
        <p>Información completa y expediente académico</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/estudiantes" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista
        </a>
        <a href="<?php echo BASE_URL; ?>/estudiantes/editar/<?= $estudiante['id'] ?>" class="btn btn-warning">
            <i class="fas fa-user-edit"></i> Editar
        </a>
    </div>
</div>

<div class="detail-grid">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-id-card"></i> Ficha de: <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></h3>
        </div>
        <div class="card-body">
            <div class="detail-section" style="border: none; padding: 0; background: none;">
                <div class="detail-items">
                    <div class="detail-item">
                        <strong>Nombre Completo:</strong>
                        <span><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Número de Registro:</strong>
                        <span><?= htmlspecialchars($estudiante['registro']) ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Correo Electrónico:</strong>
                        <span><?= htmlspecialchars($estudiante['correo']) ?: 'No especificado' ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Fecha de Registro:</strong>
                        <span><?= date('d/m/Y H:i', strtotime($estudiante['created_at'])) ?></span>
                    </div>
                     <div class="detail-item">
                        <strong>Última Actualización:</strong>
                        <span><?= date('d/m/Y H:i', strtotime($estudiante['updated_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-pie"></i> Resumen de Asistencias</h3>
        </div>
        <div class="card-body">
            <div class="stats-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 20px;">
                <div class="stat-card minimal">
                    <div class="stat-icon" style="background: var(--success);"><i class="fas fa-check"></i></div>
                    <div class="stat-info"><h3><?= $stats['presente'] ?></h3><p>Presente</p></div>
                </div>
                <div class="stat-card minimal">
                    <div class="stat-icon" style="background: var(--danger);"><i class="fas fa-times"></i></div>
                    <div class="stat-info"><h3><?= $stats['ausente'] ?></h3><p>Ausente</p></div>
                </div>
                <div class="stat-card minimal">
                    <div class="stat-icon" style="background: var(--warning);"><i class="fas fa-clock"></i></div>
                    <div class="stat-info"><h3><?= $stats['tardanza'] ?></h3><p>Tardanza</p></div>
                </div>
                <div class="stat-card minimal">
                    <div class="stat-icon" style="background: var(--info);"><i class="fas fa-file-alt"></i></div>
                    <div class="stat-info"><h3><?= $stats['justificado'] ?></h3><p>Justificado</p></div>
                </div>
            </div>

            <div class="progress-section">
                <h4>Porcentaje de Asistencia: <?= $stats['porcentaje'] ?>%</h4>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: <?= $stats['porcentaje'] ?>%;"></div>
                </div>
                <small>Calculado como (Presente + Tardanza) / (Presente + Tardanza + Ausente). Las faltas justificadas no afectan el porcentaje.</small>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card.minimal { padding: 15px; box-shadow: none; border: 1px solid var(--border); }
.stat-card.minimal .stat-icon { width: 45px; height: 45px; font-size: 1.2rem; }
.stat-card.minimal .stat-info h3 { font-size: 1.5rem; }
.progress-section { margin-top: 20px; }
.progress-section h4 { margin-bottom: 10px; }
.progress-section small { color: var(--secondary); font-size: 0.8rem; }
.progress-bar-container { height: 15px; background: var(--border); border-radius: 10px; overflow: hidden; }
.progress-bar { height: 100%; background: linear-gradient(90deg, var(--primary), var(--success)); border-radius: 10px; transition: width 0.5s ease; }
</style>

<?php require_once 'views/layout/footer.php'; ?>