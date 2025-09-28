<?php
// config/database.php - Configuración de conexión a PostgreSQL

class Database {
    // Configuración de la base de datos
    private $host = 'localhost';
    private $port = '5432';              // Puerto por defecto de PostgreSQL
    private $db_name = 'sistema_asistencias';
    private $username = 'postgres';       // Usuario por defecto
    private $password = 'root';    // CAMBIA ESTO por tu contraseña
    private $conn;
    
    /**
     * Obtiene la conexión a la base de datos
     * @return PDO|null
     */
    public function getConnection() {
        $this->conn = null;
        
        try {
            // Crear la cadena de conexión para PostgreSQL
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            
            // Crear la conexión PDO
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Configurar PDO para que lance excepciones en caso de error
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Configurar para que devuelva arrays asociativos por defecto
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch(PDOException $exception) {
            echo "Error de conexión a la base de datos: " . $exception->getMessage();
            die();
        }
        
        return $this->conn;
    }
    
    /**
     * Prueba la conexión a la base de datos
     * @return bool
     */
    public function testConnection() {
        try {
            $conn = $this->getConnection();
            if ($conn) {
                return true;
            }
        } catch(Exception $e) {
            return false;
        }
        return false;
    }
}
?>