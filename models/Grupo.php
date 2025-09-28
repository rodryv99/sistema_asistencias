<?php
// models/Grupo.php - Modelo para la tabla de grupos

require_once 'models/BaseModel.php';
require_once 'models/Estudiante.php'; // Necesario para las estadísticas

class Grupo extends BaseModel {
    
    protected $table = 'grupo';

    // ... los métodos findAll, findById, validate, create, y update permanecen igual ...

    /**
     * Encuentra todos los grupos con la información del período y materia asociados.
     * @return array
     */
    public function findAll() {
        $query = "SELECT 
                    g.*, 
                    p.nombre as periodo_nombre, 
                    m.nombre as materia_nombre,
                    m.sigla as materia_sigla
                  FROM {$this->table} g
                  LEFT JOIN periodo p ON g.periodo_id = p.id
                  LEFT JOIN materia m ON g.materia_id = m.id
                  ORDER BY p.fecha_inicio DESC, m.nombre, g.nombre";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Encuentra un grupo por su ID con la información asociada.
     * @param int $id
     * @return array|false
     */
    public function findById($id) {
        $query = "SELECT 
                    g.*, 
                    p.nombre as periodo_nombre, 
                    m.nombre as materia_nombre,
                    m.sigla as materia_sigla
                  FROM {$this->table} g
                  LEFT JOIN periodo p ON g.periodo_id = p.id
                  LEFT JOIN materia m ON g.materia_id = m.id
                  WHERE g.id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Valida los datos de un grupo.
     * @param array $data ['nombre', 'periodo_id', 'materia_id']
     * @param int|null $id ID a excluir en validaciones (para updates)
     * @return array Errores de validación
     */
    public function validate($data, $id = null) {
        $errors = [];

        if (empty($data['nombre'])) {
            $errors['nombre'] = 'El nombre del grupo es requerido.';
        }
        if (empty($data['periodo_id'])) {
            $errors['periodo_id'] = 'Debe seleccionar un período.';
        }
        if (empty($data['materia_id'])) {
            $errors['materia_id'] = 'Debe seleccionar una materia.';
        }

        // Validar que no exista un grupo con el mismo nombre para la misma materia y período
        if (empty($errors)) {
            $sql = "SELECT id FROM {$this->table} WHERE nombre = :nombre AND periodo_id = :periodo_id AND materia_id = :materia_id";
            $params = [
                ':nombre' => $data['nombre'],
                ':periodo_id' => $data['periodo_id'],
                ':materia_id' => $data['materia_id']
            ];

            if ($id !== null) {
                $sql .= " AND id != :id";
                $params[':id'] = $id;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            if ($stmt->fetch()) {
                $errors['nombre'] = 'Ya existe un grupo con este nombre para la misma materia y período.';
            }
        }

        return $errors;
    }

    /**
     * Crea un nuevo grupo.
     * @param array $data ['nombre', 'periodo_id', 'materia_id']
     * @return int|false ID del nuevo grupo o false si falla
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (nombre, periodo_id, materia_id, created_at, updated_at) 
                VALUES (:nombre, :periodo_id, :materia_id, NOW(), NOW())";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':periodo_id', $data['periodo_id'], PDO::PARAM_INT);
        $stmt->bindParam(':materia_id', $data['materia_id'], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Actualiza un grupo existente.
     * @param int $id ID del grupo
     * @param array $data ['nombre', 'periodo_id', 'materia_id']
     * @return bool True si tuvo éxito, false si no
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET nombre = :nombre, periodo_id = :periodo_id, materia_id = :materia_id, updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':periodo_id', $data['periodo_id'], PDO::PARAM_INT);
        $stmt->bindParam(':materia_id', $data['materia_id'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * NUEVO MÉTODO: Obtiene la lista de estudiantes inscritos en un grupo.
     * @param int $grupo_id
     * @return array
     */
    public function getEstudiantesInscritos($grupo_id) {
        $sql = "SELECT 
                    e.id as estudiante_id,
                    e.nombre,
                    e.apellido,
                    e.registro,
                    i.id as inscripcion_id
                FROM inscripcion i
                JOIN estudiante e ON i.estudiante_id = e.id
                WHERE i.grupo_id = :grupo_id AND i.activa = true
                ORDER BY e.apellido, e.nombre";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':grupo_id', $grupo_id, PDO::PARAM_INT);
        $stmt->execute();
        $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Para cada estudiante, calculamos sus estadísticas de asistencia EN ESTE GRUPO
        $estudianteModel = new Estudiante();
        foreach ($estudiantes as $key => $estudiante) {
            $stats = $estudianteModel->getAttendanceStats($estudiante['estudiante_id'], $grupo_id);
            $estudiantes[$key]['stats'] = $stats;
        }

        return $estudiantes;
    }

    /**
     * NUEVO MÉTODO: Obtiene la lista de estudiantes que NO están inscritos en un grupo.
     * @param int $grupo_id
     * @return array
     */
    public function getEstudiantesNoInscritos($grupo_id) {
        $sql = "SELECT * FROM estudiante 
                WHERE id NOT IN (
                    SELECT estudiante_id FROM inscripcion WHERE grupo_id = :grupo_id
                )
                ORDER BY apellido, nombre";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':grupo_id', $grupo_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>