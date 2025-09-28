<?php
// models/Estudiante.php - Modelo para la tabla de estudiantes

require_once 'models/BaseModel.php';

class Estudiante extends BaseModel {
    
    protected $table = 'estudiante';

    /**
     * Valida los datos de un estudiante.
     * @param array $data Datos del estudiante ['registro', 'nombre', 'apellido', 'correo']
     * @param int|null $id ID del estudiante a excluir en validaciones de unicidad (para updates)
     * @return array Array de errores, vacío si la validación es exitosa
     */
    public function validate($data, $id = null) {
        $errors = [];

        // Validación de Registro
        if (empty($data['registro'])) {
            $errors['registro'] = 'El número de registro es requerido.';
        } elseif (strlen($data['registro']) > 20) {
            $errors['registro'] = 'El registro no puede exceder los 20 caracteres.';
        } else {
            if ($this->isDuplicate('registro', $data['registro'], $id)) {
                $errors['registro'] = 'Este número de registro ya está en uso.';
            }
        }

        // Validación de Nombre
        if (empty($data['nombre'])) {
            $errors['nombre'] = 'El nombre es requerido.';
        } elseif (strlen($data['nombre']) > 50) {
            $errors['nombre'] = 'El nombre no puede exceder los 50 caracteres.';
        }
        
        // Validación de Apellido
        if (empty($data['apellido'])) {
            $errors['apellido'] = 'El apellido es requerido.';
        } elseif (strlen($data['apellido']) > 50) {
            $errors['apellido'] = 'El apellido no puede exceder los 50 caracteres.';
        }
        
        // Validación de Correo
        if (!empty($data['correo'])) {
            if (strlen($data['correo']) > 100) {
                $errors['correo'] = 'El correo no puede exceder los 100 caracteres.';
            } elseif (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
                $errors['correo'] = 'El formato del correo electrónico no es válido.';
            } else {
                if ($this->isDuplicate('correo', $data['correo'], $id)) {
                    $errors['correo'] = 'Este correo electrónico ya está en uso.';
                }
            }
        }

        return $errors;
    }

    /**
     * Crea un nuevo estudiante.
     * @param array $data Datos del estudiante
     * @return int|false El ID del nuevo estudiante o false si falla
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (registro, nombre, apellido, correo, created_at, updated_at) 
                VALUES (:registro, :nombre, :apellido, :correo, NOW(), NOW())";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':registro', $data['registro']);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellido', $data['apellido']);
        $stmt->bindParam(':correo', $data['correo']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Actualiza un estudiante existente.
     * @param int $id ID del estudiante
     * @param array $data Datos del estudiante
     * @return bool True si tuvo éxito, false si no
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET registro = :registro, nombre = :nombre, apellido = :apellido, correo = :correo, updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':registro', $data['registro']);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellido', $data['apellido']);
        $stmt->bindParam(':correo', $data['correo']);
        
        return $stmt->execute();
    }
    
    /**
     * Verifica si un valor es duplicado en una columna específica.
     * @param string $field Nombre de la columna (ej: 'registro' o 'correo')
     * @param string $value Valor a verificar
     * @param int|null $excludeId ID a excluir de la búsqueda (para actualizaciones)
     * @return bool True si es duplicado, false si no
     */
    private function isDuplicate($field, $value, $excludeId = null) {
        $sql = "SELECT id FROM {$this->table} WHERE {$field} = :value";
        $params = [':value' => $value];

        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch() !== false;
    }
    
    /**
     * MÉTODO ACTUALIZADO: Calcula las estadísticas de asistencia para un estudiante.
     * Ahora puede filtrar por un grupo específico.
     * @param int $estudiante_id
     * @param int|null $grupo_id (Opcional) ID del grupo para filtrar las estadísticas.
     * @return array Estadísticas de asistencia
     */
    public function getAttendanceStats($estudiante_id, $grupo_id = null) {
        $sql = "SELECT 
                    a.estado,
                    COUNT(a.id) as total
                FROM asistencia a
                JOIN inscripcion i ON a.inscripcion_id = i.id
                WHERE i.estudiante_id = :estudiante_id";
        
        $params = [':estudiante_id' => $estudiante_id];

        if ($grupo_id !== null) {
            $sql .= " AND i.grupo_id = :grupo_id";
            $params[':grupo_id'] = $grupo_id;
        }

        $sql .= " GROUP BY a.estado";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stats = [
            'presente' => 0,
            'ausente' => 0,
            'tardanza' => 0,
            'justificado' => 0,
            'porcentaje' => 0
        ];

        foreach ($results as $row) {
            if (isset($stats[$row['estado']])) {
                $stats[$row['estado']] = (int)$row['total'];
            }
        }

        $asistidas = $stats['presente'] + $stats['tardanza'];
        $total_clases_validas = $asistidas + $stats['ausente'];

        if ($total_clases_validas > 0) {
            $stats['porcentaje'] = round(($asistidas / $total_clases_validas) * 100, 1);
        }

        return $stats;
    }
}
?>