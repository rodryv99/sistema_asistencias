<?php
// controllers/MateriaController.php - Controlador para la gestión de materias

require_once 'models/Materia.php';

class MateriaController extends BaseController {
    
    private $materiaModel;
    
    public function __construct() {
        $this->materiaModel = new Materia();
    }
    
    /**
     * Muestra la lista de todas las materias.
     */
    public function index() {
        try {
            $materias = $this->materiaModel->findAll();
            $flash = $this->getFlash();
            
            $this->view('materias/index', [
                'materias' => $materias,
                'flash' => $flash,
                'title' => 'Gestión de Materias'
            ]);
            
        } catch (Exception $e) {
            $this->error('Error al cargar las materias: ' . $e->getMessage());
        }
    }
    
    /**
     * Muestra el formulario para crear una nueva materia.
     */
    public function create() {
        // LÍNEA CORREGIDA: Se cambió $this.view por $this->view
        $this->view('materias/create', [
            'title' => 'Crear Nueva Materia'
        ]);
    }
    
    /**
     * Guarda una nueva materia en la base de datos.
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/materias/crear');
        }
        
        $data = [
            'nombre' => $this->post('nombre'),
            'sigla' => $this->post('sigla')
        ];
        
        $errors = $this->materiaModel->validate($data);
        
        if (!empty($errors)) {
            $this->view('materias/create', [
                'title' => 'Crear Nueva Materia',
                'errors' => $errors,
                'data' => $data
            ]);
            return;
        }
        
        try {
            $id = $this->materiaModel->create($data);
            if ($id) {
                $this->setFlash('success', 'Materia creada exitosamente.');
                $this->redirect(BASE_URL . '/materias');
            } else {
                $this->setFlash('error', 'No se pudo crear la materia.');
                $this->redirect(BASE_URL . '/materias/crear');
            }
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al crear la materia: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/materias/crear');
        }
    }

    /**
     * Muestra los detalles de una materia específica.
     * @param int $id
     */
    public function show($id) {
        try {
            $materia = $this->materiaModel->findById($id);
            if (!$materia) {
                $this->setFlash('error', 'Materia no encontrada.');
                $this->redirect(BASE_URL . '/materias');
            }

            $this->view('materias/show', [
                'title' => 'Detalles de la Materia',
                'materia' => $materia
            ]);
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al cargar los detalles de la materia: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/materias');
        }
    }
    
    /**
     * Muestra el formulario para editar una materia.
     * @param int $id
     */
    public function edit($id) {
        try {
            $materia = $this->materiaModel->findById($id);
            if (!$materia) {
                $this->setFlash('error', 'Materia no encontrada.');
                $this->redirect(BASE_URL . '/materias');
            }
            
            $this->view('materias/edit', [
                'title' => 'Editar Materia',
                'materia' => $materia
            ]);
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al cargar la materia: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/materias');
        }
    }
    
    /**
     * Actualiza una materia existente en la base de datos.
     * @param int $id
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . "/materias/editar/{$id}");
        }

        $data = [
            'nombre' => $this->post('nombre'),
            'sigla' => $this->post('sigla')
        ];
        
        $errors = $this->materiaModel->validate($data, $id);
        
        if (!empty($errors)) {
            $materia = array_merge(['id' => $id], $data);
            $this->view('materias/edit', [
                'title' => 'Editar Materia',
                'errors' => $errors,
                'materia' => $materia
            ]);
            return;
        }
        
        try {
            $success = $this->materiaModel->update($id, $data);
            if ($success) {
                $this->setFlash('success', 'Materia actualizada exitosamente.');
                $this->redirect(BASE_URL . '/materias');
            } else {
                $this->setFlash('error', 'No se pudo actualizar la materia.');
                $this->redirect(BASE_URL . "/materias/editar/{$id}");
            }
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al actualizar la materia: ' . $e->getMessage());
            $this->redirect(BASE_URL . "/materias/editar/{$id}");
        }
    }
    
    /**
     * Elimina una materia.
     * @param int $id
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/materias');
        }
        
        try {
            $success = $this->materiaModel->delete($id);
            if ($success) {
                $this->setFlash('success', 'Materia eliminada exitosamente.');
            } else {
                $this->setFlash('error', 'No se pudo eliminar la materia. Es posible que esté asociada a un grupo.');
            }
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al eliminar la materia: ' . $e->getMessage());
        }
        
        $this->redirect(BASE_URL . '/materias');
    }
}
?>