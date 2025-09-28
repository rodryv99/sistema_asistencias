<?php 
// views/asistencias/registrar.php - Hoja de asistencia para un grupo
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-clipboard-check"></i> Registrar Asistencia</h1>
        <p>
            <strong>Grupo:</strong> <?= htmlspecialchars($grupo['nombre']) ?> | 
            <strong>Materia:</strong> <?= htmlspecialchars($grupo['materia_nombre']) ?> | 
            <strong>Período:</strong> <?= htmlspecialchars($grupo['periodo_nombre']) ?>
        </p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/asistencias" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Historial
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="form-group" style="margin-bottom: 0;">
            <label for="fecha" class="form-label" style="margin-bottom: 0;">
                <i class="fas fa-calendar-alt"></i> Asistencia para la fecha:
            </label>
            <input type="date" id="fecha" name="fecha" class="form-control" value="<?= htmlspecialchars($fecha_seleccionada) ?>" style="max-width: 200px;">
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/asistencias/guardar">
            <input type="hidden" name="grupo_id" value="<?= $grupo['id'] ?>">
            <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha_seleccionada) ?>">

            <?php if (empty($lista_asistencia)): ?>
                <div class="empty-state">
                    <i class="fas fa-user-graduate"></i>
                    <h3>No hay estudiantes inscritos</h3>
                    <p>No se puede tomar asistencia porque no hay estudiantes inscritos en este grupo.</p>
                    <a href="<?php echo BASE_URL; ?>/grupos/ver/<?= $grupo['id'] ?>" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Inscribir Estudiantes
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th style="text-align: center;">Presente</th>
                                <th style="text-align: center;">Ausente</th>
                                <th style="text-align: center;">Tardanza</th>
                                <th style="text-align: center;">Justificado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista_asistencia as $item): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($item['nombre'] . ' ' . $item['apellido']) ?></strong></td>
                                    <?php $estado = $item['estado'] ?? 'ausente'; ?>
                                    <td class="radio-cell"><input type="radio" name="asistencias[<?= $item['inscripcion_id'] ?>]" value="presente" <?= $estado == 'presente' ? 'checked' : '' ?>></td>
                                    <td class="radio-cell"><input type="radio" name="asistencias[<?= $item['inscripcion_id'] ?>]" value="ausente" <?= $estado == 'ausente' ? 'checked' : '' ?>></td>
                                    <td class="radio-cell"><input type="radio" name="asistencias[<?= $item['inscripcion_id'] ?>]" value="tardanza" <?= $estado == 'tardanza' ? 'checked' : '' ?>></td>
                                    <td class="radio-cell"><input type="radio" name="asistencias[<?= $item['inscripcion_id'] ?>]" value="justificado" <?= $estado == 'justificado' ? 'checked' : '' ?>></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Asistencia</button>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<style>
.radio-cell { text-align: center; }
.radio-cell input[type="radio"] { transform: scale(1.5); cursor: pointer; }
</style>

<script>
document.getElementById('fecha').addEventListener('change', function() {
    const fechaSeleccionada = this.value;
    const grupoId = <?= $grupo['id'] ?>;
    window.location.href = `<?php echo BASE_URL; ?>/asistencias/registrar/${grupoId}?fecha=${fechaSeleccionada}`;
});
</script>

<?php require_once 'views/layout/footer.php'; ?>