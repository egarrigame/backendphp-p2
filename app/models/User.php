<?php
/**
 * MODELO DE USUARIOS
 * Gestiona registro, login y perfil de usuarios
 */
class User extends Model {
    protected $table = 'usuarios';
    
    /**
     * Buscar usuario por email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    /**
     * Registrar nuevo usuario
     */
    public function register($data) {
        // Verificar si el email ya existe
        if ($this->findByEmail($data['email'])) {
            return false;
        }
        
        // Encriptar contraseña
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("
            INSERT INTO usuarios (nombre, email, password, rol, telefono) 
            VALUES (:nombre, :email, :password, :rol, :telefono)
        ");
        
        return $stmt->execute([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'rol' => 'particular',
            'telefono' => $data['telefono'] ?? null
        ]);
    }
    
    /**
     * Iniciar sesión
     */
    public function login($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['rol'];
            $_SESSION['user_telefono'] = $user['telefono'];
            return true;
        }
        return false;
    }
    
    /**
     * Cerrar sesión
     */
    public function logout() {
        $_SESSION = [];
        session_destroy();
    }
    
    /**
     * Obtener usuario por ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}