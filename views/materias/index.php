<?php 
// views/materias/index.php - Lista de materias
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-book"></i> Gestión de Materias</h1>
        <p>Administra las materias o asignaturas del sistema</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/materias/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Nueva Materia
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Lista de Materias</h3>
        <div class="card-tools">
            <input type="text" id="searchInput" placeholder="Buscar materia o sigla..." class="search-input">
        </div>
    </div>
    
    <div class="card-body">
        <?php if (empty($materias)): ?>
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <h3>No hay materias registradas</h3>
                <p>Comienza creando tu primera materia académica</p>
                <a href="<?php echo BASE_URL; ?>/materias/crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Primera Materia
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" id="materiasTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Sigla</th>
                            <th>Creado en</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materias as $materia): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($materia['nombre']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge badge-info"><?= htmlspecialchars($materia['sigla']) ?></span>
                                </td>
                                <td>
                                    <span class="date-badge">
                                        <i class="fas fa-calendar-day"></i>
                                        <?= date('d/m/Y', strtotime($materia['created_at'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo BASE_URL; ?>/materias/ver/<?= $materia['id'] ?>" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/materias/editar/<?= $materia['id'] ?>" 
                                           class="btn btn-sm btn-warning" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmarEliminacion(<?= $materia['id'] ?>, '<?= htmlspecialchars($materia['nombre']) ?>')" 
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
    const tableRows = document.querySelectorAll('#materiasTable tbody tr');
    
    tableRows.forEach(function(row) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Función para confirmar eliminación
function confirmarEliminacion(id, nombre) {
    showConfirmModal(
        'Confirmar Eliminación',
        `¿Estás seguro de que deseas eliminar la materia "<strong>${nombre}</strong>"?<br><br>
        <div class="warning-text">
            <i class="fas fa-exclamation-triangle"></i>
            Esta acción no se puede deshacer.
        </div>`,
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo BASE_URL; ?>/materias/eliminar/' + id;
            document.body.appendChild(form);
            form.submit();
        },
        'danger'
    );
}
</script>

<?php require_once 'views/layout/footer.php'; ?>