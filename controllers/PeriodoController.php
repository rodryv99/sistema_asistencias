<?php
// controllers/PeriodoController.php - Controlador para gestión de períodos

require_once 'controllers/BaseController.php';
require_once 'models/Periodo.php';

class PeriodoController extends BaseController {
    
    private $periodoModel;
    
    public function __construct() {
        $this->periodoModel = new Periodo();
    }
    
    /**
     * Mostrar lista de períodos
     */
    public function index() {
        try {
            $periodos = $this->periodoModel->findAll();
            $flash = $this->getFlash();
            
            $this->view('periodos/index', [
                'periodos' => $periodos,
                'flash' => $flash,
                'title' => 'Gestión de Períodos'
            ]);
            
        } catch (Exception $e) {
            $this->error('Error al cargar los períodos: ' . $e->getMessage());
        }
    }
    
    /**
     * Mostrar formulario para crear período
     */
    public function create() {
        $this->view('periodos/create', [
            'title' => 'Crear Nuevo Período'
        ]);
    }
    
    /**
     * Procesar creación de período
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/periodos/crear');
        }
        
        // Validar campos requeridos
        $requiredFields = ['nombre', 'fecha_inicio', 'fecha_fin'];
        $errors = $this->validateRequired($requiredFields);
        
        // Obtener datos del formulario
        $data = [
            'nombre' => $this->post('nombre'),
            'fecha_inicio' => $this->post('fecha_inicio'),
            'fecha_fin' => $this->post('fecha_fin'),
            'activo' => $this->post('activo') ? true : false
        ];
        
        // Validaciones adicionales
        if (empty($errors)) {
            // Validar fechas
            $dateErrors = $this->periodoModel->validateDates($data['fecha_inicio'], $data['fecha_fin']);
            $errors = array_merge($errors, $dateErrors);
        }
        
        // Si hay errores, volver al formulario
        if (!empty($errors)) {
            $this->view('periodos/create', [
                'title' => 'Crear Nuevo Período',
                'errors' => $errors,
                'data' => $data
            ]);
            return;
        }
        
        // Intentar crear el período
        try {
            $id = $this->periodoModel->create($data);
            
            if ($id) {
                $this->setFlash('success', 'Período creado exitosamente');
                $this->redirect(BASE_URL . '/periodos');
            } else {
                $this->setFlash('error', 'Error al crear el período');
                $this->redirect(BASE_URL . '/periodos/crear');
            }
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al crear el período: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/periodos/crear');
        }
    }
    
    /**
     * Mostrar formulario para editar período
     * @param int $id
     */
    public function edit($id) {
        if (!$id) {
            $this->setFlash('error', 'ID de período no válido');
            $this->redirect(BASE_URL . '/periodos');
        }
        
        try {
            $periodo = $this->periodoModel->findById($id);
            
            if (!$periodo) {
                $this->setFlash('error', 'Período no encontrado');
                $this->redirect(BASE_URL . '/periodos');
            }
            
            $this->view('periodos/edit', [
                'title' => 'Editar Período',
                'periodo' => $periodo
            ]);
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al cargar el período: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/periodos');
        }
    }
    
    /**
     * Procesar actualización de período
     * @param int $id
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . "/periodos/editar/{$id}");
        }
        
        if (!$id) {
            $this->setFlash('error', 'ID de período no válido');
            $this->redirect(BASE_URL . '/periodos');
        }
        
        // Verificar que el período existe
        $periodo = $this->periodoModel->findById($id);
        if (!$periodo) {
            $this->setFlash('error', 'Período no encontrado');
            $this->redirect(BASE_URL . '/periodos');
        }
        
        // Validar campos requeridos
        $requiredFields = ['nombre', 'fecha_inicio', 'fecha_fin'];
        $errors = $this->validateRequired($requiredFields);
        
        // Obtener datos del formulario
        $data = [
            'nombre' => $this->post('nombre'),
            'fecha_inicio' => $this->post('fecha_inicio'),
            'fecha_fin' => $this->post('fecha_fin'),
            'activo' => $this->post('activo') ? true : false
        ];
        
        // Validaciones adicionales
        if (empty($errors)) {
            // Validar fechas (excluyendo el registro actual)
            $dateErrors = $this->periodoModel->validateDates($data['fecha_inicio'], $data['fecha_fin'], $id);
            $errors = array_merge($errors, $dateErrors);
        }
        
        // Si hay errores, volver al formulario
        if (!empty($errors)) {
            $this->view('periodos/edit', [
                'title' => 'Editar Período',
                'errors' => $errors,
                'periodo' => array_merge($periodo, $data)
            ]);
            return;
        }
        
        // Intentar actualizar el período
        try {
            $success = $this->periodoModel->update($id, $data);
            
            if ($success) {
                $this->setFlash('success', 'Período actualizado exitosamente');
                $this->redirect(BASE_URL . '/periodos');
            } else {
                $this->setFlash('error', 'Error al actualizar el período');
                $this->redirect(BASE_URL . "/periodos/editar/{$id}");
            }
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al actualizar el período: ' . $e->getMessage());
            $this->redirect(BASE_URL . "/periodos/editar/{$id}");
        }
    }
    
    /**
     * Eliminar período
     * @param int $id
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/periodos');
        }
        
        if (!$id) {
            $this->setFlash('error', 'ID de período no válido');
            $this->redirect(BASE_URL . '/periodos');
        }
        
        try {
            // Verificar que el período existe
            if (!$this->periodoModel->exists($id)) {
                $this->setFlash('error', 'Período no encontrado');
                $this->redirect(BASE_URL . '/periodos');
            }
            
            // Verificar que se puede eliminar
            if (!$this->periodoModel->canDelete($id)) {
                $this->setFlash('error', 'No se puede eliminar el período porque tiene grupos asociados');
                $this->redirect(BASE_URL . '/periodos');
            }
            
            // Eliminar
            $success = $this->periodoModel->delete($id);
            
            if ($success) {
                $this->setFlash('success', 'Período eliminado exitosamente');
            } else {
                $this->setFlash('error', 'Error al eliminar el período');
            }
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al eliminar el período: ' . $e->getMessage());
        }
        
        $this->redirect(BASE_URL . '/periodos');
    }
    
    /**
     * Mostrar detalles de un período
     * @param int $id
     */
    public function show($id) {
        if (!$id) {
            $this->setFlash('error', 'ID de período no válido');
            $this->redirect(BASE_URL . '/periodos');
        }
        
        try {
            $periodo = $this->periodoModel->findById($id);
            
            if (!$periodo) {
                $this->setFlash('error', 'Período no encontrado');
                $this->redirect(BASE_URL . '/periodos');
            }
            
            $this->view('periodos/show', [
                'title' => 'Detalles del Período',
                'periodo' => $periodo
            ]);
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al cargar el período: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/periodos');
        }
    }
}
?>