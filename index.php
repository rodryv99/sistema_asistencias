<?php
// index.php - Punto de entrada principal del sistema

// Define la URL base para que todos los enlaces y recursos funcionen correctamente.
define('BASE_URL', '/sistema_asistencias');

// Iniciar sesión
session_start();

// Incluir archivos necesarios
require_once 'config/database.php';
require_once 'config/routes.php';
require_once 'controllers/BaseController.php';
require_once 'models/BaseModel.php';


// Crear el router
$router = new Router();

// --- Definir todas las rutas del sistema ---

// Rutas para Períodos
$router->addRoute('GET', '/', 'PeriodoController', 'index');
$router->addRoute('GET', '/periodos', 'PeriodoController', 'index');
$router->addRoute('GET', '/periodos/crear', 'PeriodoController', 'create');
$router->addRoute('POST', '/periodos', 'PeriodoController', 'store');
$router->addRoute('GET', '/periodos/ver/:id', 'PeriodoController', 'show');
$router->addRoute('GET', '/periodos/editar/:id', 'PeriodoController', 'edit');
$router->addRoute('POST', '/periodos/actualizar/:id', 'PeriodoController', 'update');
$router->addRoute('POST', '/periodos/eliminar/:id', 'PeriodoController', 'delete');

// Rutas para Materias
$router->addRoute('GET', '/materias', 'MateriaController', 'index');
$router->addRoute('GET', '/materias/crear', 'MateriaController', 'create');
$router->addRoute('POST', '/materias', 'MateriaController', 'store');
$router->addRoute('GET', '/materias/ver/:id', 'MateriaController', 'show');
$router->addRoute('GET', '/materias/editar/:id', 'MateriaController', 'edit');
$router->addRoute('POST', '/materias/actualizar/:id', 'MateriaController', 'update');
$router->addRoute('POST', '/materias/eliminar/:id', 'MateriaController', 'delete');

// Rutas para Estudiantes
$router->addRoute('GET', '/estudiantes', 'EstudianteController', 'index');
$router->addRoute('GET', '/estudiantes/crear', 'EstudianteController', 'create');
$router->addRoute('POST', '/estudiantes', 'EstudianteController', 'store');
$router->addRoute('GET', '/estudiantes/ver/:id', 'EstudianteController', 'show');
$router->addRoute('GET', '/estudiantes/editar/:id', 'EstudianteController', 'edit');
$router->addRoute('POST', '/estudiantes/actualizar/:id', 'EstudianteController', 'update');
$router->addRoute('POST', '/estudiantes/eliminar/:id', 'EstudianteController', 'delete');

// Rutas para Grupos
$router->addRoute('GET', '/grupos', 'GrupoController', 'index');
$router->addRoute('GET', '/grupos/crear', 'GrupoController', 'create');
$router->addRoute('POST', '/grupos', 'GrupoController', 'store');
$router->addRoute('GET', '/grupos/ver/:id', 'GrupoController', 'show');
$router->addRoute('GET', '/grupos/editar/:id', 'GrupoController', 'edit');
$router->addRoute('POST', '/grupos/actualizar/:id', 'GrupoController', 'update');
$router->addRoute('POST', '/grupos/eliminar/:id', 'GrupoController', 'delete');
// NUEVAS RUTAS PARA MANEJAR INSCRIPCIONES DENTRO DE GRUPOS
$router->addRoute('POST', '/grupos/inscribir/:id', 'GrupoController', 'inscribirEstudiante');
$router->addRoute('POST', '/grupos/quitar/:id', 'GrupoController', 'quitarInscripcion');


// RUTAS DEL MÓDULO DE INSCRIPCIONES (ELIMINADAS)


// Rutas para Asistencias
$router->addRoute('GET', '/asistencias', 'AsistenciaController', 'index'); // Historial
$router->addRoute('GET', '/asistencias/crear', 'AsistenciaController', 'create'); // Selección de grupo
$router->addRoute('GET', '/asistencias/registrar/:id', 'AsistenciaController', 'registrar'); // Hoja de lista
$router->addRoute('POST', '/asistencias/guardar', 'AsistenciaController', 'guardar'); // Guardar lista
$router->addRoute('GET', '/asistencias/editar/:id', 'AsistenciaController', 'edit'); // Editar registro individual
$router->addRoute('POST', '/asistencias/actualizar/:id', 'AsistenciaController', 'update'); // Actualizar registro
$router->addRoute('POST', '/asistencias/eliminar/:id', 'AsistenciaController', 'delete'); // Eliminar registro


// --- Fin de la definición de rutas ---


// Obtener la URL y método de la petición
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Limpiar la URI para que el router la entienda
$baseUrl = '/sistema_asistencias';
if (strpos($uri, $baseUrl) === 0) {
    $uri = substr($uri, strlen($baseUrl));
}
if (empty($uri)) {
    $uri = '/';
}


// Ejecutar la ruta correspondiente
try {
    $router->dispatch($method, $uri);
} catch (Exception $e) {
    // Error 500
    http_response_code(500);
    // Es útil mostrar un error detallado en desarrollo
    echo "<h1>Error 500 - Error Interno del Servidor</h1>";
    echo "<p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . " en la línea " . $e->getLine() . "</p>";
    echo "<pre><strong>Traza:</strong><br>" . $e->getTraceAsString() . "</pre>";
}
?>