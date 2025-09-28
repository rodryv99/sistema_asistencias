<?php 
// views/grupos/create.php - Formulario para crear un nuevo grupo
require_once 'views/layout/header.php'; 
?>

<div class="page-header">
    <div class="header-content">
        <h1><i class="fas fa-plus-circle"></i> Crear Nuevo Grupo</h1>
        <p>Asigna una materia a un período académico</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/grupos" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-chalkboard"></i> Información del Grupo</h3>
    </div>
    
    <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>/grupos" id="createGrupoForm" novalidate>
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre" class="form-label"><i class="fas fa-tag"></i> Nombre del Grupo *</label>
                    <input type="text" id="nombre" name="nombre" class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" value="<?= isset($data['nombre']) ? htmlspecialchars($data['nombre']) : '' ?>" placeholder="Ej: Grupo A" required>
                    <?php if (isset($errors['nombre'])): ?><div class="invalid-feedback"><?= $errors['nombre'] ?></div><?php endif; ?>
                    <div class="form-help">Identificador del grupo (A, B, C, etc.).</div>
                </div>

                <div class="form-group">
                    <label for="periodo_id" class="form-label"><i class="fas fa-calendar-alt"></i> Período Académico *</label>
                    <select id="periodo_id" name="periodo_id" class="form-control <?= isset($errors['periodo_id']) ? 'is-invalid' : '' ?>" required>
                        <option value="">-- Selecciona un período --</option>
                        <?php foreach ($periodos as $periodo): ?>
                            <option value="<?= $periodo['id'] ?>" <?= (isset($data['periodo_id']) && $data['periodo_id'] == $periodo['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($periodo['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['periodo_id'])): ?><div class="invalid-feedback"><?= $errors['periodo_id'] ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="materia_id" class="form-label"><i class="fas fa-book"></i> Materia *</label>
                    <select id="materia_id" name="materia_id" class="form-control <?= isset($errors['materia_id']) ? 'is-invalid' : '' ?>" required>
                        <option value="">-- Selecciona una materia --</option>
                        <?php foreach ($materias as $materia): ?>
                            <option value="<?= $materia['id'] ?>" <?= (isset($data['materia_id']) && $data['materia_id'] == $materia['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($materia['nombre']) ?> (<?= htmlspecialchars($materia['sigla']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['materia_id'])): ?><div class="invalid-feedback"><?= $errors['materia_id'] ?></div><?php endif; ?>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?php echo BASE_URL; ?>/grupos" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Crear Grupo</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>