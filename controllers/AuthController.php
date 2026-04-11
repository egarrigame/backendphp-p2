<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // Mostrar login
    public function showLogin(): void
    {
        $this->render('auth/login');
    }

    // Procesar login
    public function login(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validación básica
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            $this->redirect('/login');
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Credenciales incorrectas';
            $this->redirect('/login');
        }

        // Guardar sesión
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nombre' => $user['nombre'],
            'email' => $user['email'],
            'rol' => $user['rol']
        ];

        // Redirección por rol
        switch ($user['rol']) {
            case 'admin':
                $this->redirect('/admin/dashboard');
                break;

            case 'tecnico':
                $this->redirect('/tecnico/agenda');
                break;

            default:
                $this->redirect('/cliente/dashboard');
                break;
        }
    }

    // Mostrar registro
    public function showRegister(): void
    {
        $this->render('auth/register');
    }

    // Procesar registro
    public function register(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/register');
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validación
        if (empty($nombre) || empty($email) || empty($telefono) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            $this->redirect('/register');
        }

        // Verificar si ya existe
        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = 'El email ya está registrado';
            $this->redirect('/register');
        }

        // Hash de contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $created = $this->userModel->create([
            'nombre' => $nombre,
            'email' => $email,
            'password' => $hashedPassword,
            'telefono' => $telefono,
            'rol' => 'particular'
        ]);

        if (!$created) {
            $_SESSION['error'] = 'Error al registrar usuario';
            $this->redirect('/register');
        }

        $_SESSION['success'] = 'Registro completado, ahora puedes iniciar sesión';
        $this->redirect('/login');
    }

    // Logout
    public function logout(): void
    {
        session_destroy();
        $this->redirect('/login');
    }
}