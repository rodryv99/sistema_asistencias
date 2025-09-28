<?php 
// views/grupos/show.php - Vista de detalles de un grupo
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-eye"></i> Detalles del Grupo</h1>
        <p>Información del grupo y lista de estudiantes inscritos</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/asistencias/registrar/<?= $grupo['id'] ?>" class="btn btn-success"><i class="fas fa-check-circle"></i> Tomar Asistencia</a>
        <a href="<?php echo BASE_URL; ?>/grupos" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver a la Lista</a>
        <a href="<?php echo BASE_URL; ?>/grupos/editar/<?= $grupo['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Editar Grupo</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-chalkboard-teacher"></i> Grupo <?= htmlspecialchars($grupo['nombre']) ?></h3>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-section">
                <h4><i class="fas fa-info-circle"></i> Información del Grupo</h4>
                <div class="detail-items">
                    <div class="detail-item">
                        <strong>Materia:</strong>
                        <span><?= htmlspecialchars($grupo['materia_nombre']) ?> (<?= htmlspecialchars($grupo['materia_sigla']) ?>)</span>
                    </div>
                    <div class="detail-item">
                        <strong>Período:</strong>
                        <span><?= htmlspecialchars($grupo['periodo_nombre']) ?></span>
                    </div>
                </div>
            </div>
            <div class="detail-section">
                <h4><i class="fas fa-users"></i> Resumen de Estudiantes</h4>
                <div class="detail-items">
                    <div class="detail-item">
                        <strong>Estudiantes Inscritos:</strong>
                        <span class="count-badge"><i class="fas fa-user-check"></i> <?= count($estudiantesInscritos) ?></span>
                    </div>
                     <div class="detail-item">
                        <strong>Disponibles para inscribir:</strong>
                        <span class="count-badge" style="background-color: rgba(243, 156, 18, 0.1); color: var(--warning);"><i class="fas fa-user-plus"></i> <?= count($estudiantesNoInscritos) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-graduate"></i> Estudiantes Inscritos</h3>
    </div>
    <div class="card-body">
        <div class="card" style="margin-bottom: 30px; background: #fdfdfd;">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>/grupos/inscribir/<?= $grupo['id'] ?>" method="POST" class="form-inline">
                    <div class="form-group">
                        <label for="estudiante_id" class="form-label">Inscribir estudiante al grupo:</label>
                        <select name="estudiante_id" id="estudiante_id" class="form-control" required>
                            <option value="">-- Seleccionar estudiante --</option>
                            <?php if (empty($estudiantesNoInscritos)): ?>
                                <option disabled>No hay más estudiantes para inscribir</option>
                            <?php else: ?>
                                <?php foreach ($estudiantesNoInscritos as $estudiante): ?>
                                    <option value="<?= $estudiante['id'] ?>"><?= htmlspecialchars($estudiante['apellido'] . ', ' . $estudiante['nombre']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success" <?= empty($estudiantesNoInscritos) ? 'disabled' : '' ?>><i class="fas fa-plus"></i> Inscribir</button>
                </form>
            </div>
        </div>

        <?php if (empty($estudiantesInscritos)): ?>
            <div class="empty-state">
                <p>Aún no hay estudiantes inscritos en este grupo.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Registro</th>
                            <th style="width: 200px;">% Asistencia</th>
                            <th>Presente</th>
                            <th>Ausente</th>
                            <th>Tardanza</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantesInscritos as $estudiante): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($estudiante['apellido'] . ', ' . $estudiante['nombre']) ?></strong></td>
                                <td><?= htmlspecialchars($estudiante['registro']) ?></td>
                                <td>
                                    <div class="progress-bar-container" title="<?= $estudiante['stats']['porcentaje'] ?>% de asistencia">
                                        <div class="progress-bar" style="width: <?= $estudiante['stats']['porcentaje'] ?>%;">
                                            <span><?= $estudiante['stats']['porcentaje'] ?>%</span>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center;"><?= $estudiante['stats']['presente'] ?></td>
                                <td style="text-align: center;"><?= $estudiante['stats']['ausente'] ?></td>
                                <td style="text-align: center;"><?= $estudiante['stats']['tardanza'] ?></td>
                                <td>
                                    <form action="<?php echo BASE_URL; ?>/grupos/quitar/<?= $estudiante['inscripcion_id'] ?>" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres quitar a este estudiante del grupo?');">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Quitar del grupo"><i class="fas fa-user-times"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.form-inline { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
.form-inline .form-group { flex: 1; margin-bottom: 0; }
/* CAMBIO CLAVE: El texto de la barra ahora es oscuro y está dentro de un <span> para mejor control */
.progress-bar span { 
    color: var(--dark); 
    font-size: 0.8rem; 
    font-weight: bold; 
    text-shadow: 1px 1px 1px rgba(255,255,255,0.5);
    padding-left: 5px;
}
</style>

<?php require_once 'views/layout/footer.php'; ?>