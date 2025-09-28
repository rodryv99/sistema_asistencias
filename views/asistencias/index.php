<?php 
// views/asistencias/index.php - Historial de todos los registros de asistencia
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-history"></i> Historial de Asistencias</h1>
        <p>Todos los registros de asistencia del sistema</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/asistencias/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tomar Asistencia
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Registros de Asistencia</h3>
        <div class="card-tools">
            <input type="text" id="searchInput" placeholder="Buscar..." class="search-input">
        </div>
    </div>
    
    <div class="card-body">
        <?php if (empty($asistencias)): ?>
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <h3>No hay asistencias registradas</h3>
                <p>Comienza tomando asistencia en un grupo.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" id="asistenciasTable">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Estudiante</th>
                            <th>Materia</th>
                            <th>Grupo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asistencias as $asistencia): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($asistencia['fecha'])) ?></td>
                                <td><?= htmlspecialchars($asistencia['estudiante_nombre'] . ' ' . $asistencia['estudiante_apellido']) ?></td>
                                <td><?= htmlspecialchars($asistencia['materia_nombre']) ?></td>
                                <td><?= htmlspecialchars($asistencia['grupo_nombre']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $asistencia['estado'] == 'presente' ? 'success' : ($asistencia['estado'] == 'ausente' ? 'danger' : 'warning') ?>">
                                        <?= ucfirst($asistencia['estado']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo BASE_URL; ?>/asistencias/editar/<?= $asistencia['id'] ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                                        <button onclick="confirmarEliminacion(<?= $asistencia['id'] ?>)" class="btn btn-sm btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', e => {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('#asistenciasTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
    });
});

function confirmarEliminacion(id) {
    showConfirmModal('Confirmar Eliminación', '¿Estás seguro de que deseas eliminar este registro de asistencia?', () => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?php echo BASE_URL; ?>/asistencias/eliminar/${id}`;
        document.body.appendChild(form);
        form.submit();
    }, 'danger');
}
</script>

<?php require_once 'views/layout/footer.php'; ?>