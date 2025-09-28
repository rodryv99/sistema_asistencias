<?php
// models/Asistencia.php - Modelo para la tabla de asistencias

require_once 'models/BaseModel.php';

class Asistencia extends BaseModel {
    
    protected $table = 'asistencia';

    /**
     * Encuentra todas las asistencias con información detallada.
     * @return array
     */
    public function findAll() {
        $query = "SELECT 
                    a.id, a.fecha, a.estado,
                    e.nombre as estudiante_nombre, e.apellido as estudiante_apellido,
                    g.nombre as grupo_nombre,
                    m.nombre as materia_nombre, m.sigla as materia_sigla,
                    p.nombre as periodo_nombre
                  FROM {$this->table} a
                  JOIN inscripcion i ON a.inscripcion_id = i.id
                  JOIN estudiante e ON i.estudiante_id = e.id
                  JOIN grupo g ON i.grupo_id = g.id
                  JOIN materia m ON g.materia_id = m.id
                  JOIN periodo p ON g.periodo_id = p.id
                  ORDER BY a.fecha DESC, a.id DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Encuentra un registro de asistencia por su ID con toda la información relacionada.
     * @param int $id
     * @return array|false
     */
    public function findById($id) {
        $query = "SELECT 
                    a.*,
                    i.id as inscripcion_id,
                    e.nombre as estudiante_nombre, e.apellido as estudiante_apellido, e.registro as estudiante_registro,
                    g.id as grupo_id, g.nombre as grupo_nombre,
                    m.nombre as materia_nombre
                  FROM {$this->table} a
                  JOIN inscripcion i ON a.inscripcion_id = i.id
                  JOIN estudiante e ON i.estudiante_id = e.id
                  JOIN grupo g ON i.grupo_id = g.id
                  JOIN materia m ON g.materia_id = m.id
                  WHERE a.id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Actualiza un único registro de asistencia.
     * @param int $id
     * @param array $data ['fecha', 'estado']
     * @return bool
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET fecha = :fecha, estado = :estado, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':fecha', $data['fecha']);
        $stmt->bindParam(':estado', $data['estado']);
        return $stmt->execute();
    }

    /**
     * Encuentra los estudiantes de un grupo para una fecha específica (para tomar lista).
     * @param int $grupo_id
     * @param string $fecha (formato Y-m-d)
     * @return array
     */
    public function findByGrupoAndFecha($grupo_id, $fecha) {
        $query = "SELECT
                    a.id as asistencia_id, a.estado,
                    i.id as inscripcion_id,
                    e.id as estudiante_id, e.nombre, e.apellido, e.registro
                  FROM inscripcion i
                  JOIN estudiante e ON i.estudiante_id = e.id
                  LEFT JOIN asistencia a ON a.inscripcion_id = i.id AND a.fecha = :fecha
                  WHERE i.grupo_id = :grupo_id AND i.activa = true
                  ORDER BY e.apellido, e.nombre";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':grupo_id', $grupo_id, PDO::PARAM_INT);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Guarda o actualiza un lote de asistencias para un grupo y fecha.
     * @param int $grupo_id
     * @param string $fecha
     * @param array $asistencias - ej: ['inscripcion_id' => 'estado', ...]
     * @return bool
     */
    public function guardarAsistencias($grupo_id, $fecha, $asistencias) {
        $this->db->beginTransaction();
        try {
            $sqlDelete = "DELETE FROM {$this->table} WHERE fecha = :fecha AND inscripcion_id IN 
                          (SELECT id FROM inscripcion WHERE grupo_id = :grupo_id)";
            $stmtDelete = $this->db->prepare($sqlDelete);
            $stmtDelete->bindParam(':fecha', $fecha);
            $stmtDelete->bindParam(':grupo_id', $grupo_id, PDO::PARAM_INT);
            $stmtDelete->execute();

            $sqlInsert = "INSERT INTO {$this->table} (inscripcion_id, fecha, estado, created_at, updated_at) 
                          VALUES (:inscripcion_id, :fecha, :estado, NOW(), NOW())";
            $stmtInsert = $this->db->prepare($sqlInsert);

            foreach ($asistencias as $inscripcion_id => $estado) {
                $stmtInsert->bindParam(':inscripcion_id', $inscripcion_id, PDO::PARAM_INT);
                $stmtInsert->bindParam(':fecha', $fecha);
                $stmtInsert->bindParam(':estado', $estado);
                $stmtInsert->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
?>