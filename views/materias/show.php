<?php 
// views/materias/show.php - Vista de detalles de una materia
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-eye"></i> Detalles de la Materia</h1>
        <p>Información completa de la asignatura</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/materias" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista
        </a>
        <a href="<?php echo BASE_URL; ?>/materias/editar/<?= $materia['id'] ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-book-reader"></i> <?= htmlspecialchars($materia['nombre']) ?></h3>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-section">
                <h4><i class="fas fa-info-circle"></i> Información General</h4>
                <div class="detail-items">
                    <div class="detail-item">
                        <strong>Nombre:</strong>
                        <span><?= htmlspecialchars($materia['nombre']) ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Sigla:</strong>
                        <span><?= htmlspecialchars($materia['sigla']) ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Fecha de Creación:</strong>
                        <span><?= date('d/m/Y H:i', strtotime($materia['created_at'])) ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Última Actualización:</strong>
                        <span><?= date('d/m/Y H:i', strtotime($materia['updated_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>