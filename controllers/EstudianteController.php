<?php
// controllers/EstudianteController.php - Controlador para la gestión de estudiantes

require_once 'models/Estudiante.php';

class EstudianteController extends BaseController {
    
    private $estudianteModel;
    
    public function __construct() {
        $this->estudianteModel = new Estudiante();
    }
    
    /**
     * Muestra la lista de todos los estudiantes.
     */
    public function index() {
        try {
            $estudiantes = $this->estudianteModel->findAll();
            $flash = $this->getFlash();
            
            $this->view('estudiantes/index', [
                'estudiantes' => $estudiantes,
                'flash' => $flash,
                'title' => 'Gestión de Estudiantes'
            ]);
            
        } catch (Exception $e) {
            $this->error('Error al cargar los estudiantes: ' . $e->getMessage());
        }
    }
    
    /**
     * Muestra el formulario para crear un nuevo estudiante.
     */
    public function create() {
        $this->view('estudiantes/create', [
            'title' => 'Registrar Nuevo Estudiante'
        ]);
    }
    
    /**
     * Guarda un nuevo estudiante en la base de datos.
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/estudiantes/crear');
        }
        
        $data = [
            'registro' => $this->post('registro'),
            'nombre' => $this->post('nombre'),
            'apellido' => $this->post('apellido'),
            'correo' => $this->post('correo')
        ];
        
        $errors = $this->estudianteModel->validate($data);
        
        if (!empty($errors)) {
            $this->view('estudiantes/create', [
                'title' => 'Registrar Nuevo Estudiante',
                'errors' => $errors,
                'data' => $data
            ]);
            return;
        }
        
        try {
            $id = $this->estudianteModel->create($data);
            if ($id) {
                $this->setFlash('success', 'Estudiante registrado exitosamente.');
                $this->redirect(BASE_URL . '/estudiantes');
            } else {
                $this->setFlash('error', 'No se pudo registrar al estudiante.');
                $this->redirect(BASE_URL . '/estudiantes/crear');
            }
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al registrar al estudiante: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/estudiantes/crear');
        }
    }

    /**
     * Muestra los detalles de un estudiante.
     * @param int $id
     */
    public function show($id) {
        try {
            $estudiante = $this->estudianteModel->findById($id);
            if (!$estudiante) {
                $this->setFlash('error', 'Estudiante no encontrado.');
                $this->redirect(BASE_URL . '/estudiantes');
            }
            
            // MÉTODO MODIFICADO: Obtener y pasar las estadísticas
            $stats = $this->estudianteModel->getAttendanceStats($id);

            $this->view('estudiantes/show', [
                'title' => 'Detalles del Estudiante',
                'estudiante' => $estudiante,
                'stats' => $stats
            ]);
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al cargar los detalles del estudiante: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/estudiantes');
        }
    }
    
    /**
     * Muestra el formulario para editar un estudiante.
     * @param int $id
     */
    public function edit($id) {
        try {
            $estudiante = $this->estudianteModel->findById($id);
            if (!$estudiante) {
                $this->setFlash('error', 'Estudiante no encontrado.');
                $this->redirect(BASE_URL . '/estudiantes');
            }
            
            $this->view('estudiantes/edit', [
                'title' => 'Editar Estudiante',
                'estudiante' => $estudiante
            ]);
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al cargar al estudiante: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/estudiantes');
        }
    }
    
    /**
     * Actualiza un estudiante existente.
     * @param int $id
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . "/estudiantes/editar/{$id}");
        }

        $data = [
            'registro' => $this->post('registro'),
            'nombre' => $this->post('nombre'),
            'apellido' => $this->post('apellido'),
            'correo' => $this->post('correo')
        ];
        
        $errors = $this->estudianteModel->validate($data, $id);
        
        if (!empty($errors)) {
            $estudiante = array_merge(['id' => $id], $data);
            $this->view('estudiantes/edit', [
                'title' => 'Editar Estudiante',
                'errors' => $errors,
                'estudiante' => $estudiante
            ]);
            return;
        }
        
        try {
            $success = $this->estudianteModel->update($id, $data);
            if ($success) {
                $this->setFlash('success', 'Estudiante actualizado exitosamente.');
                $this->redirect(BASE_URL . '/estudiantes');
            } else {
                $this->setFlash('error', 'No se pudo actualizar al estudiante.');
                $this->redirect(BASE_URL . "/estudiantes/editar/{$id}");
            }
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al actualizar al estudiante: ' . $e->getMessage());
            $this->redirect(BASE_URL . "/estudiantes/editar/{$id}");
        }
    }
    
    /**
     * Elimina un estudiante.
     * @param int $id
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/estudiantes');
        }
        
        try {
            $success = $this->estudianteModel->delete($id);
            if ($success) {
                $this->setFlash('success', 'Estudiante eliminado exitosamente.');
            } else {
                $this->setFlash('error', 'No se pudo eliminar al estudiante. Es posible que tenga inscripciones activas.');
            }
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al eliminar al estudiante: ' . $e->getMessage());
        }
        
        $this->redirect(BASE_URL . '/estudiantes');
    }
}
?>