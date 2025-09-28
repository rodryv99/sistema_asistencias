<?php 
// views/grupos/index.php - Lista de grupos
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-layer-group"></i> Gestión de Grupos</h1>
        <p>Administra los grupos de materias por período académico</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/grupos/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Nuevo Grupo
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Lista de Grupos</h3>
        <div class="card-tools">
            <input type="text" id="searchInput" placeholder="Buscar por grupo, materia, período..." class="search-input">
        </div>
    </div>
    
    <div class="card-body">
        <?php if (empty($grupos)): ?>
            <div class="empty-state">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>No hay grupos registrados</h3>
                <p>Comienza creando tu primer grupo para una materia y período</p>
                <a href="<?php echo BASE_URL; ?>/grupos/crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Primer Grupo
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" id="gruposTable">
                    <thead>
                        <tr>
                            <th>Grupo</th>
                            <th>Materia</th>
                            <th>Período Académico</th>
                            <th>Fecha de Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grupos as $grupo): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($grupo['nombre']) ?></strong>
                                </td>
                                <td>
                                    <?= htmlspecialchars($grupo['materia_nombre']) ?>
                                    <span class="badge badge-secondary"><?= htmlspecialchars($grupo['materia_sigla']) ?></span>
                                </td>
                                <td>
                                    <?= htmlspecialchars($grupo['periodo_nombre']) ?>
                                </td>
                                <td>
                                    <span class="date-badge">
                                        <i class="fas fa-calendar-day"></i>
                                        <?= date('d/m/Y', strtotime($grupo['created_at'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo BASE_URL; ?>/grupos/ver/<?= $grupo['id'] ?>" class="btn btn-sm btn-info" title="Ver detalles"><i class="fas fa-eye"></i></a>
                                        <a href="<?php echo BASE_URL; ?>/grupos/editar/<?= $grupo['id'] ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                                        <button onclick="confirmarEliminacion(<?= $grupo['id'] ?>, '<?= htmlspecialchars($grupo['nombre']) ?>', '<?= htmlspecialchars($grupo['materia_nombre']) ?>')" class="btn btn-sm btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
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
    const tableRows = document.querySelectorAll('#gruposTable tbody tr');
    
    tableRows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
    });
});

// Función para confirmar eliminación
function confirmarEliminacion(id, nombre, materia) {
    showConfirmModal(
        'Confirmar Eliminación',
        `¿Estás seguro de que deseas eliminar el grupo "<strong>${nombre}</strong>" de la materia "<strong>${materia}</strong>"?`,
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?php echo BASE_URL; ?>/grupos/eliminar/${id}`;
            document.body.appendChild(form);
            form.submit();
        },
        'danger'
    );
}
</script>

<?php require_once 'views/layout/footer.php'; ?>