<?php
class Router {
    private $routes = [];
    
    public function add($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function dispatch($method, $uri) {
        $uri = trim($uri, '/');
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $uri) {
                $controllerName = $route['controller'];
                $actionName = $route['action'];
                
                $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $controller = new $controllerName();
                    $controller->$actionName();
                    return;
                }
            }
        }
        
        http_response_code(404);
        echo "404 - Página no encontrada";
    }
}