<?php
// controllers/GrupoController.php - Controlador para la gestión de grupos

require_once 'models/Grupo.php';
require_once 'models/Periodo.php';
require_once 'models/Materia.php';
require_once 'models/Inscripcion.php';

class GrupoController extends BaseController {
    
    private $grupoModel;
    private $periodoModel;
    private $materiaModel;
    private $inscripcionModel;
    
    public function __construct() {
        $this->grupoModel = new Grupo();
        $this->periodoModel = new Periodo();
        $this->materiaModel = new Materia();
        $this->inscripcionModel = new Inscripcion();
    }
    
    public function index() {
        try {
            $grupos = $this->grupoModel->findAll();
            $flash = $this->getFlash();
            $this->view('grupos/index', ['grupos' => $grupos, 'flash' => $flash, 'title' => 'Gestión de Grupos']);
        } catch (Exception $e) { $this->error('Error al cargar los grupos: ' . $e->getMessage()); }
    }
    public function create() {
        try {
            $periodos = $this->periodoModel->findAll();
            $materias = $this->materiaModel->findAll();
            $this->view('grupos/create', ['title' => 'Crear Nuevo Grupo', 'periodos' => $periodos, 'materias' => $materias]);
        } catch (Exception $e) { $this->error('Error al preparar el formulario de creación: ' . $e->getMessage()); }
    }
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect(BASE_URL . '/grupos/crear');
        $data = ['nombre' => $this->post('nombre'), 'periodo_id' => $this->post('periodo_id'), 'materia_id' => $this->post('materia_id')];
        $errors = $this->grupoModel->validate($data);
        if (!empty($errors)) {
            $periodos = $this->periodoModel->findAll();
            $materias = $this->materiaModel->findAll();
            $this->view('grupos/create', ['title' => 'Crear Nuevo Grupo', 'errors' => $errors, 'data' => $data, 'periodos' => $periodos, 'materias' => $materias]);
            return;
        }
        try {
            $id = $this->grupoModel->create($data);
            if ($id) $this->setFlash('success', 'Grupo creado exitosamente.');
            else $this->setFlash('error', 'No se pudo crear el grupo.');
            $this->redirect(BASE_URL . '/grupos');
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al crear el grupo: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/grupos/crear');
        }
    }

    /**
     * MÉTODO ACTUALIZADO: Muestra los detalles de un grupo, incluyendo sus estudiantes.
     * @param int $id
     */
    public function show($id) {
        try {
            $grupo = $this->grupoModel->findById($id);
            if (!$grupo) {
                $this->setFlash('error', 'Grupo no encontrado.');
                $this->redirect(BASE_URL . '/grupos');
            }
            $estudiantesInscritos = $this->grupoModel->getEstudiantesInscritos($id);
            $estudiantesNoInscritos = $this->grupoModel->getEstudiantesNoInscritos($id);
            $flash = $this->getFlash();
            $this->view('grupos/show', [
                'title' => 'Detalles del Grupo',
                'grupo' => $grupo,
                'estudiantesInscritos' => $estudiantesInscritos,
                'estudiantesNoInscritos' => $estudiantesNoInscritos,
                'flash' => $flash
            ]);
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al cargar los detalles del grupo: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/grupos');
        }
    }
    
    public function edit($id) {
        try {
            $grupo = $this->grupoModel->findById($id);
            if (!$grupo) { $this->setFlash('error', 'Grupo no encontrado.'); $this->redirect(BASE_URL . '/grupos'); }
            $periodos = $this->periodoModel->findAll();
            $materias = $this->materiaModel->findAll();
            $this->view('grupos/edit', ['title' => 'Editar Grupo', 'grupo' => $grupo, 'periodos' => $periodos, 'materias' => $materias]);
        } catch (Exception $e) { $this->setFlash('error', 'Error al cargar el grupo: ' . $e->getMessage()); $this->redirect(BASE_URL . '/grupos'); }
    }
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect(BASE_URL . "/grupos/editar/{$id}");
        $data = ['nombre' => $this->post('nombre'), 'periodo_id' => $this->post('periodo_id'), 'materia_id' => $this->post('materia_id')];
        $errors = $this->grupoModel->validate($data, $id);
        if (!empty($errors)) {
            $grupoData = $this->grupoModel->findById($id);
            $grupo = array_merge($grupoData, $data);
            $periodos = $this->periodoModel->findAll();
            $materias = $this->materiaModel->findAll();
            $this->view('grupos/edit', ['title' => 'Editar Grupo', 'errors' => $errors, 'grupo' => $grupo, 'periodos' => $periodos, 'materias' => $materias]);
            return;
        }
        try {
            $success = $this->grupoModel->update($id, $data);
            if ($success) $this->setFlash('success', 'Grupo actualizado exitosamente.');
            else $this->setFlash('error', 'No se pudo actualizar el grupo.');
            $this->redirect(BASE_URL . '/grupos');
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al actualizar el grupo: ' . $e->getMessage());
            $this->redirect(BASE_URL . "/grupos/editar/{$id}");
        }
    }
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect(BASE_URL . '/grupos');
        try {
            $success = $this->grupoModel->delete($id);
            if ($success) $this->setFlash('success', 'Grupo eliminado exitosamente.');
            else $this->setFlash('error', 'No se pudo eliminar el grupo. Es posible que tenga estudiantes inscritos.');
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al eliminar el grupo: ' . $e->getMessage());
        }
        $this->redirect(BASE_URL . '/grupos');
    }

    /**
     * NUEVO MÉTODO: Inscribe un estudiante a un grupo.
     * @param int $grupo_id
     */
    public function inscribirEstudiante($grupo_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estudiante_id = $this->post('estudiante_id');
            $data = ['grupo_id' => $grupo_id, 'estudiante_id' => $estudiante_id];
            $errors = $this->inscripcionModel->validate($data);
            if (empty($errors)) {
                $this->inscripcionModel->create($data);
                $this->setFlash('success', 'Estudiante inscrito exitosamente.');
            } else {
                $this->setFlash('error', $errors['duplicado'] ?? 'Error al inscribir al estudiante.');
            }
        }
        $this->redirect(BASE_URL . "/grupos/ver/{$grupo_id}");
    }
    
    /**
     * NUEVO MÉTODO: Quita a un estudiante de un grupo (elimina la inscripción).
     * @param int $inscripcion_id
     */
    public function quitarInscripcion($inscripcion_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inscripcion = $this->inscripcionModel->findById($inscripcion_id);
            $grupo_id = $inscripcion ? $inscripcion['grupo_id'] : null;
            $this->inscripcionModel->delete($inscripcion_id);
            $this->setFlash('success', 'Estudiante quitado del grupo exitosamente.');
            if ($grupo_id) $this->redirect(BASE_URL . "/grupos/ver/{$grupo_id}");
        }
        $this->redirect(BASE_URL . '/grupos');
    }
}
?>