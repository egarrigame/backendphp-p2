<?php
// src/models/Usuario.php

class Usuario {
    private $db; // variable para guardar la conexión a la bbdd

    public function __construct($conexionDB) { // instanciamos la clase con la conexión a la bbdd
        $this->db = $conexionDB;
    }

    public function registrar($nombre, $email, $password, $telefono) { // método para registrar un nuevo usuario
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT); // bycrypt para hash de contraseña

            $sql = "INSERT INTO usuarios (nombre, email, password, telefono, rol)
                    VALUES (:nombre, :email, :password, :telefono, 'particular')"; // consulta sql con marcadores (evitar inyecciones)
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':nombre', $nombre); // vinculación de los datos con los marcadores de la consulta
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $passwordHash);
            $stmt->bindParam(':telefono', $telefono);

            return $stmt->execute(); // ejecutamos la consulta
            
        } catch (PDOException $e) {
            return false; // devolvemos false si hay error en la consulta, como correo existente o fallo de conexión
        }
    }

    public function login($email, $password) { // método para verificar credenciales
        $sql = "SELECT * FROM usuarios WHERE email = :email"; // buscamos correo en la bbdd
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // nos devuelve array con los datos si lo encuentra

        if ($usuario && password_verify($password, $usuario['password'])) { // si existe, verificamos contraseña
            unset($usuario['password']); 
            return $usuario; // devolvemos el usuario
        }
        return false;
    }
}