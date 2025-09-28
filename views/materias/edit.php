<?php 
// views/materias/edit.php - Formulario para editar una materia
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-edit"></i> Editar Materia</h1>
        <p>Modifica los datos de la asignatura</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/materias" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-book-reader"></i> Editando: <?= htmlspecialchars($materia['nombre']) ?></h3>
    </div>
    
    <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/materias/actualizar/<?= $materia['id'] ?>" id="editMateriaForm" novalidate>
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-tag"></i> Nombre de la Materia *
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($materia['nombre']) ?>"
                           placeholder="Ej: Programación Avanzada"
                           maxlength="100"
                           required>
                    <?php if (isset($errors['nombre'])): ?>
                        <div class="invalid-feedback"><?= $errors['nombre'] ?></div>
                    <?php endif; ?>
                    <div class="form-help">
                        Nombre completo de la asignatura.
                    </div>
                </div>

                <div class="form-group">
                    <label for="sigla" class="form-label">
                        <i class="fas fa-code"></i> Sigla o Código *
                    </label>
                    <input type="text" 
                           id="sigla" 
                           name="sigla" 
                           class="form-control <?= isset($errors['sigla']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($materia['sigla']) ?>"
                           placeholder="Ej: PROG-AVZ"
                           maxlength="20"
                           required>
                    <?php if (isset($errors['sigla'])): ?>
                        <div class="invalid-feedback"><?= $errors['sigla'] ?></div>
                    <?php endif; ?>
                    <div class="form-help">
                        Código único para identificar la materia.
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?php echo BASE_URL; ?>/materias" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Materia
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>