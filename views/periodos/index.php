<?php 
// views/periodos/index.php - Lista de períodos
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-calendar-alt"></i> Gestión de Períodos</h1>
        <p>Administra los períodos académicos del sistema</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/periodos/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Nuevo Período
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-info">
            <h3><?= count(array_filter($periodos, function($p) { return $p['activo']; })) ?></h3>
            <p>Períodos Activos</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar"></i>
        </div>
        <div class="stat-info">
            <h3><?= count($periodos) ?></h3>
            <p>Total Períodos</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Lista de Períodos</h3>
        <div class="card-tools">
            <input type="text" id="searchInput" placeholder="Buscar período..." class="search-input">
        </div>
    </div>
    
    <div class="card-body">
        <?php if (empty($periodos)): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-plus"></i>
                <h3>No hay períodos registrados</h3>
                <p>Comienza creando tu primer período académico</p>
                <a href="<?php echo BASE_URL; ?>/periodos/crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Primer Período
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" id="periodosTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                            <th>Duración</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($periodos as $periodo): ?>
                            <tr>
                                <td><?= $periodo['id'] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($periodo['nombre']) ?></strong>
                                </td>
                                <td>
                                    <span class="date-badge">
                                        <i class="fas fa-calendar-day"></i>
                                        <?= date('d/m/Y', strtotime($periodo['fecha_inicio'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="date-badge">
                                        <i class="fas fa-calendar-day"></i>
                                        <?= date('d/m/Y', strtotime($periodo['fecha_fin'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($periodo['activo']): ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-pause-circle"></i> Inactivo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                        $inicio = new DateTime($periodo['fecha_inicio']);
                                        $fin = new DateTime($periodo['fecha_fin']);
                                        $duracion = $inicio->diff($fin)->days;
                                    ?>
                                    <span class="duration-badge">
                                        <i class="fas fa-clock"></i> <?= $duracion ?> días
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo BASE_URL; ?>/periodos/ver/<?= $periodo['id'] ?>" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/periodos/editar/<?= $periodo['id'] ?>" 
                                           class="btn btn-sm btn-warning" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmarEliminacion(<?= $periodo['id'] ?>, '<?= htmlspecialchars($periodo['nombre']) ?>')" 
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

<div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación</h3>
            <button class="modal-close" onclick="cerrarModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>¿Estás seguro de que deseas eliminar el período <strong id="periodoNombre"></strong>?</p>
            <p class="warning-text">
                <i class="fas fa-warning"></i>
                Esta acción no se puede deshacer y se eliminarán todos los datos asociados.
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
            <form id="deleteForm" method="POST" style="display: inline;">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Definimos la URL base en JavaScript para usarla en el formulario
const baseUrl = '<?php echo BASE_URL; ?>';

// Función para buscar en la tabla
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#periodosTable tbody tr');
    
    tableRows.forEach(function(row) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Función para confirmar eliminación (RUTA CORREGIDA)
function confirmarEliminacion(id, nombre) {
    document.getElementById('periodoNombre').textContent = nombre;
    document.getElementById('deleteForm').action = baseUrl + '/periodos/eliminar/' + id;
    document.getElementById('deleteModal').style.display = 'flex';
}

// Función para cerrar modal
function cerrarModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Cerrar modal al hacer clic fuera de él
window.addEventListener('click', function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        cerrarModal();
    }
});
</script>

<?php require_once 'views/layout/footer.php'; ?>