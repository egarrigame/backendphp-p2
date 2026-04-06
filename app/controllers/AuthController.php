<?php
class AuthController extends Controller {
    
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function showLogin() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login');
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if ($this->userModel->login($email, $password)) {
            switch ($_SESSION['user_role']) {
                case 'admin':
                    $this->redirect('/admin/dashboard');
                    break;
                case 'tecnico':
                    $this->redirect('/tecnico/agenda');
                    break;
                default:
                    $this->redirect('/dashboard');
            }
        } else {
            $_SESSION['errors'] = ['Email o contraseña incorrectos'];
            $this->redirect('/login');
        }
    }
    
    public function showRegister() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/register');
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }
        
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $telefono = trim($_POST['telefono'] ?? '');
        
        $errors = [];
        
        if (empty($nombre)) $errors[] = 'El nombre es obligatorio';
        if (empty($email)) $errors[] = 'El email es obligatorio';
        if (empty($password)) $errors[] = 'La contraseña es obligatoria';
        if (strlen($password) < 6) $errors[] = 'La contraseña debe tener al menos 6 caracteres';
        if ($password !== $password_confirm) $errors[] = 'Las contraseñas no coinciden';
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            $this->redirect('/register');
        }
        
        $userData = [
            'nombre' => $nombre,
            'email' => $email,
            'password' => $password,
            'telefono' => $telefono
        ];
        
        if ($this->userModel->register($userData)) {
            $_SESSION['success'] = 'Registro exitoso. Ahora puedes iniciar sesión.';
            $this->redirect('/login');
        } else {
            $_SESSION['errors'] = ['El email ya está registrado'];
            $this->redirect('/register');
        }
    }
    
    public function logout() {
        $this->userModel->logout();
        $this->redirect('/login');
    }
}