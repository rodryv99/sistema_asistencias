// public/js/main.js - JavaScript principal del sistema

document.addEventListener('DOMContentLoaded', function() {
    
    // Inicializar componentes
    initializeNavigation();
    initializeAlerts();
    initializeForms();
    
});

/**
 * Inicializar navegación móvil
 */
function initializeNavigation() {
    const mobileMenu = document.getElementById('mobile-menu');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenu && navMenu) {
        mobileMenu.addEventListener('click', function() {
            mobileMenu.classList.toggle('is-active');
            navMenu.classList.toggle('active');
        });
        
        // Cerrar menú al hacer clic en un enlace
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('is-active');
                navMenu.classList.remove('active');
            });
        });
    }
}

/**
 * Inicializar sistema de alertas
 */
function initializeAlerts() {
    // Auto-cerrar alertas después de 5 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        // Solo auto-cerrar alertas de éxito e info
        if (alert.classList.contains('alert-success') || alert.classList.contains('alert-info')) {
            setTimeout(() => {
                fadeOutAlert(alert);
            }, 5000);
        }
        
        // Botón de cerrar manual
        const closeBtn = alert.querySelector('.alert-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                fadeOutAlert(alert);
            });
        }
    });
}

/**
 * Desvanecer y ocultar alerta
 */
function fadeOutAlert(alert) {
    alert.style.opacity = '0';
    alert.style.transform = 'translateY(-20px)';
    setTimeout(() => {
        alert.style.display = 'none';
    }, 300);
}

/**
 * Inicializar funcionalidad de formularios
 */
function initializeForms() {
    // Validación en tiempo real
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('.form-control');
        
        inputs.forEach(input => {
            // Remover clase de error al empezar a escribir
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    const feedback = this.parentNode.querySelector('.invalid-feedback');
                    if (feedback) {
                        feedback.remove();
                    }
                }
            });
            
            // Validación al perder el foco
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });
    });
    
    // Prevenir envío de formularios con errores
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const isValid = validateForm(this);
            if (!isValid) {
                e.preventDefault();
                scrollToFirstError();
            }
        });
    });
}

/**
 * Validar un campo individual
 */
function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.getAttribute('name');
    const isRequired = field.hasAttribute('required');
    
    // Limpiar errores previos
    field.classList.remove('is-invalid');
    const existingFeedback = field.parentNode.querySelector('.invalid-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }
    
    let isValid = true;
    let errorMessage = '';
    
    // Validar campos requeridos
    if (isRequired && !value) {
        isValid = false;
        errorMessage = `El campo ${getFieldLabel(field)} es requerido`;
    }
    
    // Validar email
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
            errorMessage = 'Formato de email inválido';
        }
    }
    
    // Validar fechas
    if (field.type === 'date' && value) {
        // SE ELIMINÓ EL BLOQUE QUE RESTRINGÍA LA FECHA DE INICIO A HOY
        
        if (fieldName === 'fecha_fin') {
            const fechaInicio = document.querySelector('[name="fecha_inicio"]');
            if (fechaInicio && fechaInicio.value && value <= fechaInicio.value) {
                isValid = false;
                errorMessage = 'La fecha de fin debe ser posterior a la fecha de inicio';
            }
        }
    }
    
    // Mostrar error si no es válido
    if (!isValid) {
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

/**
 * Validar todo el formulario
 */
function validateForm(form) {
    const fields = form.querySelectorAll('.form-control[required], .form-control[type="email"]');
    let isValid = true;
    
    fields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    return isValid;
}

/**
 * Mostrar error en un campo
 */
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    
    const feedback = document.createElement('div');
    feedback.className = 'invalid-feedback';
    feedback.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    
    field.parentNode.appendChild(feedback);
}

/**
 * Obtener etiqueta del campo
 */
function getFieldLabel(field) {
    const label = field.parentNode.querySelector('label');
    if (label) {
        return label.textContent.replace('*', '').trim();
    }
    return field.getAttribute('name') || 'campo';
}

/**
 * Scroll al primer error
 */
function scrollToFirstError() {
    const firstError = document.querySelector('.is-invalid');
    if (firstError) {
        firstError.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
        firstError.focus();
    }
}

/**
 * Funciones utilitarias globales
 */

// Formatear fecha para mostrar
window.formatDate = function(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

// Calcular diferencia de días entre fechas
window.daysDifference = function(date1, date2) {
    const firstDate = new Date(date1);
    const secondDate = new Date(date2);
    const diffTime = Math.abs(secondDate - firstDate);
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
};

// Mostrar modal de confirmación
window.showConfirmModal = function(title, message, onConfirm, type = 'warning') {
    const modal = createModal(title, message, onConfirm, type);
    document.body.appendChild(modal);
    
    // Mostrar modal
    setTimeout(() => {
        modal.style.display = 'flex';
    }, 10);
    
    return modal;
};

// Crear modal dinámico
function createModal(title, message, onConfirm, type = 'warning') {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>
                    <i class="fas fa-${type === 'danger' ? 'exclamation-triangle' : 'question-circle'}"></i>
                    ${title}
                </h3>
                <button class="modal-close" type="button">&times;</button>
            </div>
            <div class="modal-body">
                <p>${message}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary modal-cancel" type="button">Cancelar</button>
                <button class="btn btn-${type}" type="button">Confirmar</button>
            </div>
        </div>
    `;
    
    // Eventos del modal
    const closeBtn = modal.querySelector('.modal-close');
    const cancelBtn = modal.querySelector('.modal-cancel');
    const confirmBtn = modal.querySelector('.btn-' + type);
    
    function closeModal() {
        modal.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(modal);
        }, 300);
    }
    
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    
    confirmBtn.addEventListener('click', () => {
        if (onConfirm) onConfirm();
        closeModal();
    });
    
    // Cerrar al hacer clic fuera
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    return modal;
}

// Mostrar notificación toast
window.showToast = function(message, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Estilos del toast
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        animation: slideInRight 0.3s ease;
        background: ${type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : '#3498db'};
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    document.body.appendChild(toast);
    
    // Remover después de la duración especificada
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, duration);
};

// Agregar estilos de animación para toast
if (!document.querySelector('#toast-styles')) {
    const style = document.createElement('style');
    style.id = 'toast-styles';
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        .toast-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    `;
    document.head.appendChild(style);
}