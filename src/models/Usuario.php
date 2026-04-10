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

    public function obtenerClientes() { // método para obtener usuarios
        $sql = "SELECT id, nombre, email FROM usuarios WHERE rol = 'particular' ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) { // método para obtener los datos del usuario
        $sql = "SELECT id, nombre, email, rol FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPerfil($id, $nombre, $email, $password = null) { // método para actualizar los datos
        try {
            if (!empty($password)) { // actualización de contraseña con bycrypt
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET nombre = :nombre, email = :email, password = :password WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':password', $passwordHash);
            } else {
                $sql = "UPDATE usuarios SET nombre = :nombre, email = :email WHERE id = :id";
                $stmt = $this->db->prepare($sql);
            }

            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function crearTecnico($nombre, $email, $telefono, $password, $especialidad_id) { // método para crear un técnico como admin
        try {
            $this->db->beginTransaction();

            $passwordHash = password_hash($password, PASSWORD_DEFAULT); // creación del user con login
            $sql1 = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, 'tecnico')";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':telefono' => $telefono,
                ':password' => $passwordHash
            ]);
            
            $nuevo_usuario_id = $this->db->lastInsertId(); // id de mysql

            // creación de la ficha del nuevo user tecnico
            $sql2 = "INSERT INTO tecnicos (usuario_id, nombre_completo, especialidad_id, disponible) VALUES (:uid, :nombre, :esp_id, 1)";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute([
                ':uid' => $nuevo_usuario_id,
                ':nombre' => $nombre,
                ':esp_id' => $especialidad_id
            ]);

            $this->db->commit(); // confrimamos cambios y creamos
            return true;
            
        } catch (PDOException $e) { // si hay un error, rollback completo
            $this->db->rollBack();
            return false;
        }
    }

    public function actualizarEstadoTecnico($tecnico_id, $nuevo_estado) { // método para cambiar el estado de un te´cnico por admin
        $sql = "UPDATE tecnicos SET disponible = :estado WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':estado' => $nuevo_estado,
            ':id' => $tecnico_id
        ]);
    }
}