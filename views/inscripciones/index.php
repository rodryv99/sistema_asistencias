<?php 
// views/inscripciones/index.php - Lista de inscripciones
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-user-plus"></i> Gestión de Inscripciones</h1>
        <p>Administra las inscripciones de estudiantes a los grupos</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/inscripciones/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Realizar Inscripción
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Lista de Inscripciones</h3>
        <div class="card-tools">
            <input type="text" id="searchInput" placeholder="Buscar por estudiante, materia, grupo..." class="search-input">
        </div>
    </div>
    
    <div class="card-body">
        <?php if (empty($inscripciones)): ?>
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <h3>No hay inscripciones registradas</h3>
                <p>Comienza inscribiendo a tu primer estudiante en un grupo</p>
                <a href="<?php echo BASE_URL; ?>/inscripciones/crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Realizar Primera Inscripción
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" id="inscripcionesTable">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Grupo</th>
                            <th>Materia</th>
                            <th>Período</th>
                            <th>Fecha de Inscripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inscripciones as $inscripcion): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($inscripcion['estudiante_nombre'] . ' ' . $inscripcion['estudiante_apellido']) ?></strong><br>
                                    <small class="text-secondary">Reg: <?= htmlspecialchars($inscripcion['estudiante_registro']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($inscripcion['grupo_nombre']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['materia_nombre']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['periodo_nombre']) ?></td>
                                <td>
                                    <span class="date-badge">
                                        <i class="fas fa-calendar-day"></i>
                                        <?= date('d/m/Y', strtotime($inscripcion['fecha_inscripcion'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($inscripcion['activa']): ?>
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Activa</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary"><i class="fas fa-times-circle"></i> Inactiva</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button onclick="confirmarEliminacion(<?= $inscripcion['id'] ?>, '<?= htmlspecialchars($inscripcion['estudiante_nombre']) ?>', '<?= htmlspecialchars($inscripcion['materia_nombre']) ?>')" class="btn btn-sm btn-danger" title="Eliminar Inscripción">
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
    const tableRows = document.querySelectorAll('#inscripcionesTable tbody tr');
    
    tableRows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
    });
});

// Función para confirmar eliminación
function confirmarEliminacion(id, estudiante, materia) {
    showConfirmModal(
        'Confirmar Eliminación',
        `¿Estás seguro de que deseas eliminar la inscripción de <strong>${estudiante}</strong> a la materia <strong>${materia}</strong>?`,
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?php echo BASE_URL; ?>/inscripciones/eliminar/${id}`;
            document.body.appendChild(form);
            form.submit();
        },
        'danger'
    );
}
</script>

<?php require_once 'views/layout/footer.php'; ?>