<?php
/**
 * CONTROLADOR BASE
 * 
 * Todos los controladores heredan de esta clase
 * Proporciona métodos comunes como redirecciones, vistas y autenticación
 */

class Controller {
    
    /**
     * Cargar una vista
     * 
     * @param string $view Nombre de la vista (ej: 'auth/login')
     * @param array $data Datos para pasar a la vista
     */
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . "/../views/{$view}.php";
        $viewFile = str_replace('\\', '/', $viewFile);
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Vista no encontrada: {$view}");
        }
    }
    
    /**
     * Redirigir a otra URL
     * 
     * @param string $url URL de destino
     */
    protected function redirect($url) {
        header("Location: {$url}");
        exit();
    }
    
    /**
     * Verificar si el usuario ha iniciado sesión
     * 
     * @return bool True si hay sesión activa
     */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Requerir que el usuario haya iniciado sesión
     * Si no, redirige al login
     */
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
    }
    
    /**
     * Requerir un rol específico
     * 
     * @param string $role Rol requerido (admin, tecnico, particular)
     */
    protected function requireRole($role) {
        $this->requireLogin();
        if ($_SESSION['user_role'] !== $role) {
            $this->redirect('/dashboard');
        }
    }
}