<?php
// models/Inscripcion.php - Modelo para la tabla de inscripciones

require_once 'models/BaseModel.php';

class Inscripcion extends BaseModel {
    
    protected $table = 'inscripcion';

    /**
     * Encuentra todas las inscripciones con información detallada de las tablas relacionadas.
     * @return array
     */
    public function findAll() {
        $query = "SELECT 
                    i.id, i.fecha_inscripcion, i.activa,
                    e.nombre as estudiante_nombre, e.apellido as estudiante_apellido, e.registro as estudiante_registro,
                    g.nombre as grupo_nombre,
                    m.nombre as materia_nombre, m.sigla as materia_sigla,
                    p.nombre as periodo_nombre
                  FROM {$this->table} i
                  JOIN estudiante e ON i.estudiante_id = e.id
                  JOIN grupo g ON i.grupo_id = g.id
                  JOIN materia m ON g.materia_id = m.id
                  JOIN periodo p ON g.periodo_id = p.id
                  ORDER BY i.id DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Valida los datos de una inscripción.
     * @param array $data ['estudiante_id', 'grupo_id']
     * @return array Errores de validación
     */
    public function validate($data) {
        $errors = [];

        if (empty($data['estudiante_id'])) {
            $errors['estudiante_id'] = 'Debe seleccionar un estudiante.';
        }
        if (empty($data['grupo_id'])) {
            $errors['grupo_id'] = 'Debe seleccionar un grupo.';
        }

        // Validar que el estudiante no esté ya inscrito en ese grupo
        if (empty($errors)) {
            $sql = "SELECT id FROM {$this->table} WHERE estudiante_id = :estudiante_id AND grupo_id = :grupo_id";
            $params = [
                ':estudiante_id' => $data['estudiante_id'],
                ':grupo_id' => $data['grupo_id']
            ];

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            if ($stmt->fetch()) {
                $errors['duplicado'] = 'El estudiante ya está inscrito en este grupo.';
            }
        }

        return $errors;
    }

    /**
     * Crea una nueva inscripción.
     * @param array $data ['estudiante_id', 'grupo_id']
     * @return int|false ID de la nueva inscripción o false si falla
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (estudiante_id, grupo_id, fecha_inscripcion, created_at, updated_at) 
                VALUES (:estudiante_id, :grupo_id, NOW(), NOW(), NOW())";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':estudiante_id', $data['estudiante_id'], PDO::PARAM_INT);
        $stmt->bindParam(':grupo_id', $data['grupo_id'], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Actualiza el estado de una inscripción (activar/desactivar).
     * @param int $id ID de la inscripción
     * @param bool $estado Nuevo estado (true para activa, false para inactiva)
     * @return bool True si tuvo éxito, false si no
     */
    public function updateStatus($id, $estado) {
        $sql = "UPDATE {$this->table} 
                SET activa = :activa, updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':activa', $estado, PDO::PARAM_BOOL);
        
        return $stmt->execute();
    }
}
?>