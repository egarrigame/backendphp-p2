<?php
/**
 * CONTROLADOR DE USUARIO
 * Gestiona el perfil y la actualización de datos del usuario logueado
 */
class UserController extends Controller {
    
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Mostrar el perfil del usuario
     */
    public function profile() {
        $this->requireLogin();
        
        $user = $this->userModel->getById($_SESSION['user_id']);
        
        $this->view('profile', [
            'user' => $user,
            'pageTitle' => 'Mi Perfil'
        ]);
    }
    
    /**
     * Actualizar los datos del perfil (nombre, email, teléfono)
     */
    public function updateProfile() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
        }
        
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        
        $errors = [];
        
        // Validaciones
        if (empty($nombre)) {
            $errors[] = 'El nombre es obligatorio';
        }
        
        if (empty($email)) {
            $errors[] = 'El email es obligatorio';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El email no es válido';
        }
        
        // Verificar si el email ya existe (y no es el del usuario actual)
        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser && $existingUser['id'] != $_SESSION['user_id']) {
            $errors[] = 'El email ya está registrado por otro usuario';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect('/profile');
        }
        
        $data = [
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono
        ];
        
        if ($this->userModel->updateProfile($_SESSION['user_id'], $data)) {
            // Actualizar datos de sesión
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_telefono'] = $telefono;
            
            $_SESSION['success'] = 'Perfil actualizado correctamente';
        } else {
            $_SESSION['errors'] = ['Error al actualizar el perfil'];
        }
        
        $this->redirect('/profile');
    }
    
    /**
     * Mostrar formulario para cambiar contraseña
     */
    public function showChangePassword() {
        $this->requireLogin();
        
        $this->view('change_password', [
            'pageTitle' => 'Cambiar Contraseña'
        ]);
    }
    
    /**
     * Procesar el cambio de contraseña
     */
    public function changePassword() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/change-password');
        }
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        $errors = [];
        
        // Verificar contraseña actual
        $user = $this->userModel->getById($_SESSION['user_id']);
        if (!password_verify($currentPassword, $user['password'])) {
            $errors[] = 'La contraseña actual es incorrecta';
        }
        
        // Validar nueva contraseña
        if (empty($newPassword)) {
            $errors[] = 'La nueva contraseña es obligatoria';
        } elseif (strlen($newPassword) < 6) {
            $errors[] = 'La nueva contraseña debe tener al menos 6 caracteres';
        }
        
        // Confirmar contraseña
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Las contraseñas no coinciden';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect('/change-password');
        }
        
        if ($this->userModel->updatePassword($_SESSION['user_id'], $newPassword)) {
            $_SESSION['success'] = 'Contraseña actualizada correctamente';
        } else {
            $_SESSION['errors'] = ['Error al actualizar la contraseña'];
        }
        
        $this->redirect('/change-password');
    }
}