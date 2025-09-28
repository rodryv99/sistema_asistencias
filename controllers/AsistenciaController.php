<?php
// controllers/AsistenciaController.php - Controlador para la gestión de asistencias

require_once 'models/Asistencia.php';
require_once 'models/Grupo.php';

class AsistenciaController extends BaseController {
    
    private $asistenciaModel;
    private $grupoModel;
    
    public function __construct() {
        $this->asistenciaModel = new Asistencia();
        $this->grupoModel = new Grupo();
    }
    
    /**
     * Muestra el historial de todos los registros de asistencia.
     */
    public function index() {
        try {
            $asistencias = $this->asistenciaModel->findAll();
            $flash = $this->getFlash();
            
            $this->view('asistencias/index', [
                'asistencias' => $asistencias,
                'flash' => $flash,
                'title' => 'Historial de Asistencias'
            ]);
            
        } catch (Exception $e) {
            $this->error('Error al cargar el historial: ' . $e->getMessage());
        }
    }
    
    /**
     * Muestra la lista de grupos para seleccionar uno y tomar asistencia.
     */
    public function create() {
        try {
            $grupos = $this->grupoModel->findAll();
            $this->view('asistencias/create', [
                'grupos' => $grupos,
                'title' => 'Seleccionar Grupo para Asistencia'
            ]);
        } catch (Exception $e) {
            $this->error('Error al cargar los grupos: ' . $e->getMessage());
        }
    }
    
    /**
     * Muestra el formulario para registrar la asistencia de un grupo en una fecha.
     * @param int $grupo_id
     */
    public function registrar($grupo_id) {
        try {
            $grupo = $this->grupoModel->findById($grupo_id);
            if (!$grupo) {
                $this->setFlash('error', 'Grupo no encontrado.');
                $this->redirect(BASE_URL . '/asistencias/crear');
            }

            $fecha = $this->get('fecha', date('Y-m-d'));
            $lista_asistencia = $this->asistenciaModel->findByGrupoAndFecha($grupo_id, $fecha);
            $flash = $this->getFlash();

            $this->view('asistencias/registrar', [
                'title' => 'Registro de Asistencia',
                'grupo' => $grupo,
                'lista_asistencia' => $lista_asistencia,
                'fecha_seleccionada' => $fecha,
                'flash' => $flash
            ]);

        } catch (Exception $e) {
            $this->setFlash('error', 'Error al cargar la lista de asistencia: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/asistencias/crear');
        }
    }

    /**
     * Guarda los registros de asistencia para un grupo y fecha.
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/asistencias');
        }

        $grupo_id = $this->post('grupo_id');
        $fecha = $this->post('fecha');
        $asistencias = $this->post('asistencias', []);

        if (empty($grupo_id) || empty($fecha)) {
            $this->setFlash('error', 'Datos incompletos para guardar la asistencia.');
            $this->redirect(BASE_URL . '/asistencias/crear');
        }
        
        if(empty($asistencias)){
            $this->setFlash('warning', 'No se marcó la asistencia para ningún estudiante.');
            $this->redirect(BASE_URL . "/asistencias/registrar/{$grupo_id}?fecha={$fecha}");
        }

        try {
            $success = $this->asistenciaModel->guardarAsistencias($grupo_id, $fecha, $asistencias);
            if ($success) {
                $this->setFlash('success', 'Asistencia guardada exitosamente para la fecha ' . date('d/m/Y', strtotime($fecha)));
            } else {
                $this->setFlash('error', 'Ocurrió un error al guardar la asistencia.');
            }
        } catch (Exception $e) {
            $this->setFlash('error', 'Error crítico al guardar la asistencia: ' . $e->getMessage());
        }
        
        $this->redirect(BASE_URL . "/asistencias/registrar/{$grupo_id}?fecha={$fecha}");
    }

    /**
     * Muestra el formulario para editar un registro de asistencia.
     * @param int $id
     */
    public function edit($id) {
        try {
            $asistencia = $this->asistenciaModel->findById($id);
            if (!$asistencia) {
                $this->setFlash('error', 'Registro de asistencia no encontrado.');
                $this->redirect(BASE_URL . '/asistencias');
            }

            $this->view('asistencias/edit', [
                'title' => 'Editar Asistencia',
                'asistencia' => $asistencia
            ]);
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al cargar el registro: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/asistencias');
        }
    }

    /**
     * Actualiza un registro de asistencia.
     * @param int $id
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . "/asistencias/edit/{$id}");
        }

        $data = [
            'fecha' => $this->post('fecha'),
            'estado' => $this->post('estado')
        ];

        try {
            $success = $this->asistenciaModel->update($id, $data);
            if ($success) {
                $this->setFlash('success', 'Asistencia actualizada exitosamente.');
                $this->redirect(BASE_URL . '/asistencias');
            } else {
                $this->setFlash('error', 'No se pudo actualizar la asistencia.');
                $this->redirect(BASE_URL . "/asistencias/editar/{$id}");
            }
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al actualizar: ' . $e->getMessage());
            $this->redirect(BASE_URL . "/asistencias/editar/{$id}");
        }
    }

    /**
     * Elimina un registro de asistencia.
     * @param int $id
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/asistencias');
        }
        
        try {
            $success = $this->asistenciaModel->delete($id);
            if ($success) {
                $this->setFlash('success', 'Registro de asistencia eliminado.');
            } else {
                $this->setFlash('error', 'No se pudo eliminar el registro.');
            }
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
        
        $this->redirect(BASE_URL . '/asistencias');
    }
}
?>