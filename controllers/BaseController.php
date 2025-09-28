<?php
// controllers/BaseController.php - Controlador base

abstract class BaseController {
    
    /**
     * Carga una vista y le pasa datos.
     * @param string $viewName El nombre de la vista (ej: 'periodos/index').
     * @param array $data Datos para hacer disponibles en la vista.
     * @return void
     */
    protected function view($viewName, $data = []) {
        extract($data);
        $viewFile = "views/{$viewName}.php";
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new Exception("Vista no encontrada: {$viewFile}");
        }
    }
    
    /**
     * Redirige a otra URL.
     * @param string $url La URL de destino.
     * @return void
     */
    protected function redirect($url) {
        header("Location: {$url}");
        exit();
    }
    
    /**
     * Devuelve una respuesta en formato JSON.
     * @param array $data Los datos a codificar.
     * @return void
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    /**
     * Muestra una página de error estandarizada.
     * @param string $message El mensaje de error.
     * @param int $code El código de estado HTTP.
     * @return void
     */
    protected function error($message, $code = 500) {
        http_response_code($code);
        $this->view('error', ['message' => $message, 'code' => $code]);
        exit();
    }
    
    /**
     * Obtiene un valor del array $_POST de forma segura.
     * ESTA FUNCIÓN HA SIDO MEJORADA PARA ACEPTAR ARRAYS.
     * @param string $key La clave del dato.
     * @param mixed $default El valor por defecto si no existe.
     * @return mixed
     */
    protected function post($key, $default = null) {
        if (!isset($_POST[$key])) {
            return $default;
        }
        
        $value = $_POST[$key];
        
        // Si el valor es un array, lo devolvemos directamente.
        if (is_array($value)) {
            return $value;
        }
        
        // Si no es un array, es un string y le aplicamos trim().
        return trim($value);
    }
    
    /**
     * Obtiene un valor del array $_GET de forma segura.
     * @param string $key La clave del dato.
     * @param mixed $default El valor por defecto si no existe.
     * @return mixed
     */
    protected function get($key, $default = null) {
        return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
    }
    
    /**
     * Valida que un conjunto de campos requeridos no estén vacíos en $_POST.
     * @param array $fields Los nombres de los campos a validar.
     * @return array Un array de errores. Vacío si todo es correcto.
     */
    protected function validateRequired($fields) {
        $errors = [];
        foreach ($fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $errors[$field] = "El campo " . str_replace('_', ' ', $field) . " es requerido.";
            }
        }
        return $errors;
    }
    
    /**
     * Guarda un mensaje flash en la sesión.
     * @param string $type Tipo de mensaje (success, error, warning, info).
     * @param string $message El mensaje a mostrar.
     * @return void
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Obtiene y elimina un mensaje flash de la sesión.
     * @return array|null El mensaje flash o null si no existe.
     */
    protected function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}
?>