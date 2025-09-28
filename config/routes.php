<?php
// config/routes.php - Sistema de enrutamiento

class Router {
    private $routes = [];
    
    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function dispatch($method, $uri) {
        $uri = $this->cleanUri($uri);
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $uri)) {
                $params = $this->extractParams($route['path'], $uri);
                return $this->executeController($route['controller'], $route['action'], $params);
            }
        }
        
        $this->show404();
    }
    
    private function cleanUri($uri) {
        $uri = explode('?', $uri)[0];
        $uri = rtrim($uri, '/');
        if (empty($uri)) {
            $uri = '/';
        }
        return $uri;
    }
    
    private function matchPath($routePath, $requestPath) {
        $pattern = preg_replace('/:\w+/', '([^/]+)', $routePath);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = "/^{$pattern}$/";
        return preg_match($pattern, $requestPath, $matches);
    }
    
    private function extractParams($routePath, $requestPath) {
        $params = [];
        preg_match_all('/:(\w+)/', $routePath, $paramNames);
        
        $pattern = preg_replace('/:\w+/', '([^/]+)', $routePath);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = "/^{$pattern}$/";
        
        if (preg_match($pattern, $requestPath, $matches)) {
            array_shift($matches); // Remover el match completo
            foreach ($paramNames[1] as $index => $name) {
                $params[$name] = $matches[$index] ?? null;
            }
        }
        return $params;
    }
    
    private function executeController($controllerName, $actionName, $params = []) {
        $controllerFile = "controllers/{$controllerName}.php";
        
        if (!file_exists($controllerFile)) {
            throw new Exception("Controlador no encontrado: {$controllerName}");
        }
        require_once $controllerFile;
        
        if (!class_exists($controllerName)) {
            throw new Exception("Clase del controlador no encontrada: {$controllerName}");
        }
        $controller = new $controllerName();
        
        if (!method_exists($controller, $actionName)) {
            throw new Exception("Método no encontrado: {$controllerName}::{$actionName}");
        }
        
        // CORRECCIÓN CLAVE: Usar array_values para pasar los parámetros correctamente.
        return call_user_func_array([$controller, $actionName], array_values($params));
    }

    private function show404() {
        http_response_code(404);
        $baseUrl = defined('BASE_URL') ? BASE_URL : '';
        echo "<h1>404 - Página no encontrada</h1>";
        echo "<p>La página que buscas no existe.</p>";
        echo "<a href='{$baseUrl}/'>Volver al inicio</a>";
    }
}
?>