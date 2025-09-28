<?php
// models/Periodo.php - Modelo para gestión de períodos

require_once 'models/BaseModel.php';

class Periodo extends BaseModel {
    
    protected $table = 'periodo';

    // CONSTRUCTOR AÑADIDO para asegurar la conexión a la BD
    public function __construct() {
        parent::__construct(); // Llama al constructor de BaseModel para inicializar $this->db
    }
    
    /**
     * Crear un nuevo período
     * @param array $data - Datos del período
     * @return int|false - ID del período creado o false si hay error
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, fecha_inicio, fecha_fin, activo, created_at, updated_at) 
                  VALUES (:nombre, :fecha_inicio, :fecha_fin, :activo, NOW(), NOW())";
        
        $stmt = $this->db->prepare($query); // CORREGIDO: de conn a db
        
        $nombre = htmlspecialchars(strip_tags($data['nombre']));
        $activo = isset($data['activo']) ? (bool)$data['activo'] : false;
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':fecha_fin', $data['fecha_fin']);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_BOOL);
        
        if ($stmt->execute()) {
            return $this->getLastInsertId();
        }
        
        return false;
    }
    
    /**
     * Actualizar un período existente
     * @param int $id - ID del período
     * @param array $data - Nuevos datos
     * @return bool
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, 
                      fecha_inicio = :fecha_inicio, 
                      fecha_fin = :fecha_fin, 
                      activo = :activo,
                      updated_at = NOW()
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query); // CORREGIDO: de conn a db
        
        $nombre = htmlspecialchars(strip_tags($data['nombre']));
        $activo = isset($data['activo']) ? (bool)$data['activo'] : false;
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':fecha_fin', $data['fecha_fin']);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_BOOL);
        
        return $stmt->execute();
    }
    
    /**
     * Verificar si las fechas del período son válidas y no se solapan.
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @param int|null $excludeId - ID a excluir de la validación (para updates)
     * @return array - Array de errores
     */
    public function validateDates($fecha_inicio, $fecha_fin, $excludeId = null) {
        $errors = [];
        
        if (strtotime($fecha_inicio) >= strtotime($fecha_fin)) {
            $errors['fecha_fin'] = "La fecha de fin debe ser posterior a la fecha de inicio.";
        }
        
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE (:fecha_inicio <= fecha_fin) AND (:fecha_fin >= fecha_inicio)";
        
        if ($excludeId) {
            $query .= " AND id != :exclude_id";
        }
        
        $stmt = $this->db->prepare($query); // CORREGIDO: de conn a db
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        
        if ($stmt->fetch()) {
            $errors['solapamiento'] = "Las fechas se solapan con un período existente.";
        }
        
        return $errors;
    }
    
    /**
     * Verificar si un período puede ser eliminado (no tiene grupos asociados).
     * @param int $id
     * @return bool
     */
    public function canDelete($id) {
        $query = "SELECT COUNT(*) as total FROM grupo WHERE periodo_id = :id";
        $stmt = $this->db->prepare($query); // CORREGIDO: de conn a db
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] == 0;
    }
}
?>