<?php
// controllers/InscripcionController.php - Controlador para la gestión de inscripciones

require_once 'models/Inscripcion.php';
require_once 'models/Estudiante.php';
require_once 'models/Grupo.php';

class InscripcionController extends BaseController {
    
    private $inscripcionModel;
    private $estudianteModel;
    private $grupoModel;
    
    public function __construct() {
        $this->inscripcionModel = new Inscripcion();
        $this->estudianteModel = new Estudiante();
        $this->grupoModel = new Grupo();
    }
    
    /**
     * Muestra la lista de todas las inscripciones.
     */
    public function index() {
        try {
            $inscripciones = $this->inscripcionModel->findAll();
            $flash = $this->getFlash();
            
            $this->view('inscripciones/index', [
                'inscripciones' => $inscripciones,
                'flash' => $flash,
                'title' => 'Gestión de Inscripciones'
            ]);
            
        } catch (Exception $e) {
            $this->error('Error al cargar las inscripciones: ' . $e->getMessage());
        }
    }
    
    /**
     * Muestra el formulario para crear una nueva inscripción.
     */
    public function create() {
        try {
            // Cargar estudiantes y grupos para los menús desplegables
            $estudiantes = $this->estudianteModel->findAll();
            $grupos = $this->grupoModel->findAll();

            $this->view('inscripciones/create', [
                'title' => 'Realizar Nueva Inscripción',
                'estudiantes' => $estudiantes,
                'grupos' => $grupos
            ]);
        } catch (Exception $e) {
            $this->error('Error al preparar el formulario de inscripción: ' . $e->getMessage());
        }
    }
    
    /**
     * Guarda una nueva inscripción en la base de datos.
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/inscripciones/crear');
        }
        
        $data = [
            'estudiante_id' => $this->post('estudiante_id'),
            'grupo_id' => $this->post('grupo_id')
        ];
        
        $errors = $this->inscripcionModel->validate($data);
        
        if (!empty($errors)) {
            // Si hay errores, volver a cargar los datos para los desplegables
            $estudiantes = $this->estudianteModel->findAll();
            $grupos = $this->grupoModel->findAll();
            
            $this->view('inscripciones/create', [
                'title' => 'Realizar Nueva Inscripción',
                'errors' => $errors,
                'data' => $data,
                'estudiantes' => $estudiantes,
                'grupos' => $grupos
            ]);
            return;
        }
        
        try {
            $id = $this->inscripcionModel->create($data);
            if ($id) {
                $this->setFlash('success', 'Inscripción realizada exitosamente.');
                $this->redirect(BASE_URL . '/inscripciones');
            } else {
                $this->setFlash('error', 'No se pudo realizar la inscripción.');
                $this->redirect(BASE_URL . '/inscripciones/crear');
            }
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al guardar la inscripción: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/inscripciones/crear');
        }
    }
    
    /**
     * Elimina una inscripción.
     * @param int $id
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/inscripciones');
        }
        
        try {
            $success = $this->inscripcionModel->delete($id);
            if ($success) {
                $this->setFlash('success', 'Inscripción eliminada exitosamente.');
            } else {
                $this->setFlash('error', 'No se pudo eliminar la inscripción.');
            }
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al eliminar la inscripción: ' . $e->getMessage());
        }
        
        $this->redirect(BASE_URL . '/inscripciones');
    }
}
?>