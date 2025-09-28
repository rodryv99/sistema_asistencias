<?php 
// views/estudiantes/index.php - Lista de estudiantes
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-users"></i> Gestión de Estudiantes</h1>
        <p>Administra los registros de los estudiantes</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/estudiantes/crear" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Registrar Nuevo Estudiante
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Lista de Estudiantes</h3>
        <div class="card-tools">
            <input type="text" id="searchInput" placeholder="Buscar por nombre, registro, correo..." class="search-input">
        </div>
    </div>
    
    <div class="card-body">
        <?php if (empty($estudiantes)): ?>
            <div class="empty-state">
                <i class="fas fa-user-graduate"></i>
                <h3>No hay estudiantes registrados</h3>
                <p>Comienza registrando al primer estudiante del sistema</p>
                <a href="<?php echo BASE_URL; ?>/estudiantes/crear" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Registrar Primer Estudiante
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" id="estudiantesTable">
                    <thead>
                        <tr>
                            <th>Registro</th>
                            <th>Nombre Completo</th>
                            <th>Correo Electrónico</th>
                            <th>Fecha de Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $estudiante): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-secondary"><?= htmlspecialchars($estudiante['registro']) ?></span>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></strong>
                                </td>
                                <td>
                                    <?= htmlspecialchars($estudiante['correo']) ?: 'No especificado' ?>
                                </td>
                                <td>
                                    <span class="date-badge">
                                        <i class="fas fa-calendar-day"></i>
                                        <?= date('d/m/Y', strtotime($estudiante['created_at'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo BASE_URL; ?>/estudiantes/ver/<?= $estudiante['id'] ?>" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/estudiantes/editar/<?= $estudiante['id'] ?>" 
                                           class="btn btn-sm btn-warning" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmarEliminacion(<?= $estudiante['id'] ?>, '<?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>')" 
                                                class="btn btn-sm btn-danger" 
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
// Función para buscar en la tabla
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#estudiantesTable tbody tr');
    
    tableRows.forEach(function(row) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Función para confirmar eliminación
function confirmarEliminacion(id, nombre) {
    showConfirmModal(
        'Confirmar Eliminación',
        `¿Estás seguro de que deseas eliminar al estudiante "<strong>${nombre}</strong>"?<br><br>
        <div class="warning-text">
            <i class="fas fa-exclamation-triangle"></i>
            Esta acción no se puede deshacer y eliminará todas sus inscripciones y asistencias.
        </div>`,
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?php echo BASE_URL; ?>/estudiantes/eliminar/${id}`;
            document.body.appendChild(form);
            form.submit();
        },
        'danger'
    );
}
</script>

<?php require_once 'views/layout/footer.php'; ?>