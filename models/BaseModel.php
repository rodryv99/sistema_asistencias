<?php
// models/BaseModel.php - Modelo base

require_once 'config/database.php';

abstract class BaseModel {
    /** @var PDO La conexión a la base de datos */
    protected $db;
    
    /** @var string El nombre de la tabla asociada al modelo */
    protected $table;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    /**
     * Obtener todos los registros de la tabla.
     * @return array
     */
    public function findAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener un registro por su ID.
     * @param int $id
     * @return array|false
     */
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Eliminar un registro por su ID.
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Contar el total de registros en la tabla.
     * @return int
     */
    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    }
    
    /**
     * Verificar si un registro existe por su ID.
     * @param int $id
     * @return bool
     */
    public function exists($id) {
        $query = "SELECT 1 FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
    
    /**
     * Obtener el último ID insertado.
     * @return string
     */
    protected function getLastInsertId() {
        return $this->db->lastInsertId();
    }
}
?>