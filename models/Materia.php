<?php
// models/Materia.php - Modelo para la tabla de materias

require_once 'models/BaseModel.php';

class Materia extends BaseModel {
    
    protected $table = 'materia';

    /**
     * Valida los datos de una materia antes de crearla o actualizarla.
     * @param array $data - Datos a validar ['nombre', 'sigla']
     * @param int|null $id - El ID de la materia a excluir en la validación (para actualizaciones)
     * @return array - Array de errores, vacío si no hay errores
     */
    public function validate($data, $id = null) {
        $errors = [];

        // Validación del nombre
        if (empty($data['nombre'])) {
            $errors['nombre'] = 'El nombre de la materia es requerido.';
        } elseif (strlen($data['nombre']) > 100) {
            $errors['nombre'] = 'El nombre no puede exceder los 100 caracteres.';
        }

        // Validación de la sigla
        if (empty($data['sigla'])) {
            $errors['sigla'] = 'La sigla de la materia es requerida.';
        } elseif (strlen($data['sigla']) > 20) {
            $errors['sigla'] = 'La sigla no puede exceder los 20 caracteres.';
        } else {
            // Verificar si la sigla ya existe (y no pertenece al registro que se está editando)
            $sql = "SELECT id FROM {$this->table} WHERE sigla = :sigla";
            $params = [':sigla' => $data['sigla']];

            if ($id !== null) {
                $sql .= " AND id != :id";
                $params[':id'] = $id;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            if ($stmt->fetch()) {
                $errors['sigla'] = 'La sigla ya está en uso por otra materia.';
            }
        }

        return $errors;
    }

    /**
     * Crea una nueva materia en la base de datos.
     * @param array $data - Datos de la materia ['nombre', 'sigla']
     * @return int|false - El ID de la nueva materia o false en caso de error
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (nombre, sigla, created_at, updated_at) VALUES (:nombre, :sigla, NOW(), NOW())";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':sigla', $data['sigla']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Actualiza una materia existente en la base de datos.
     * @param int $id - El ID de la materia a actualizar
     * @param array $data - Datos de la materia ['nombre', 'sigla']
     * @return bool - True si la actualización fue exitosa, false en caso contrario
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET nombre = :nombre, sigla = :sigla, updated_at = NOW() WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':sigla', $data['sigla']);
        
        return $stmt->execute();
    }
}
?>