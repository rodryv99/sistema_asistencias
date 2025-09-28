<?php 
// views/error.php - Página de errores del sistema
require_once 'views/layout/header.php'; 
?>

<div class="error-container">
    <div class="error-content">
        <div class="error-icon">
            <?php if (isset($code) && $code == 404): ?>
                <i class="fas fa-search"></i>
            <?php else: ?>
                <i class="fas fa-exclamation-triangle"></i>
            <?php endif; ?>
        </div>
        
        <div class="error-details">
            <h1 class="error-code">
                <?= isset($code) ? $code : '500' ?>
            </h1>
            
            <h2 class="error-title">
                <?php if (isset($code) && $code == 404): ?>
                    Página no encontrada
                <?php else: ?>
                    Error del servidor
                <?php endif; ?>
            </h2>
            
            <p class="error-message">
                <?php if (isset($message)): ?>
                    <?= htmlspecialchars($message) ?>
                <?php elseif (isset($code) && $code == 404): ?>
                    La página que buscas no existe o ha sido movida.
                <?php else: ?>
                    Ha ocurrido un error interno del servidor.
                <?php endif; ?>
            </p>
            
            <div class="error-actions">
                <a href="/" class="btn btn-primary">
                    <i class="fas fa-home"></i> Ir al Inicio
                </a>
                <button onclick="window.history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver Atrás
                </button>
                <button onclick="window.location.reload()" class="btn btn-outline">
                    <i class="fas fa-redo"></i> Recargar Página
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.error-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    padding: 40px 20px;
}

.error-content {
    text-align: center;
    max-width: 600px;
}

.error-icon {
    font-size: 6rem;
    color: var(--danger);
    margin-bottom: 30px;
    opacity: 0.7;
}

.error-code {
    font-size: 4rem;
    font-weight: bold;
    color: var(--danger);
    margin: 0 0 10px 0;
    line-height: 1;
}

.error-title {
    font-size: 2rem;
    color: var(--dark);
    margin: 0 0 20px 0;
    font-weight: 600;
}

.error-message {
    font-size: 1.1rem;
    color: var(--secondary);
    margin-bottom: 40px;
    line-height: 1.6;
}

.error-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .error-icon {
        font-size: 4rem;
    }
    
    .error-code {
        font-size: 3rem;
    }
    
    .error-title {
        font-size: 1.5rem;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .error-actions .btn {
        min-width: 200px;
    }
}
</style>

<?php require_once 'views/layout/footer.php'; ?>