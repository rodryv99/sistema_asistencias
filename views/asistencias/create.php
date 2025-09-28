<?php 
// views/asistencias/create.php - Selección de grupo para tomar asistencia
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-check-circle"></i> Tomar Asistencia</h1>
        <p>Selecciona un grupo para registrar o ver la asistencia del día</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/asistencias" class="btn btn-secondary">
            <i class="fas fa-history"></i> Ver Historial
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-layer-group"></i> Paso 1: Seleccionar un Grupo</h3>
    </div>
    <div class="card-body">
        <?php if (empty($grupos)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3>No hay grupos creados</h3>
                <p>Para tomar asistencia, primero debes crear un grupo con estudiantes asignados.</p>
                <a href="<?php echo BASE_URL; ?>/grupos/crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Grupo
                </a>
            </div>
        <?php else: ?>
            <div class="action-grid">
                <?php foreach ($grupos as $grupo): ?>
                    <a href="<?php echo BASE_URL; ?>/asistencias/registrar/<?= $grupo['id'] ?>" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="action-content">
                            <h5>Grupo <?= htmlspecialchars($grupo['nombre']) ?></h5>
                            <p>
                                <?= htmlspecialchars($grupo['materia_nombre']) ?><br>
                                <small><?= htmlspecialchars($grupo['periodo_nombre']) ?></small>
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <div class="card-footer" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border); text-align: center; color: var(--secondary);">
                <i class="fas fa-info-circle"></i>
                Haz clic en un grupo para comenzar a tomar asistencia
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>