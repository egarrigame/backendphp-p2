<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Model.php';

class User extends Model
{
    protected string $table = 'usuarios';

    public function findByEmail(string $email): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        return $this->fetch($sql, ['email' => $email]);
    }

    public function findById(int $id): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        return $this->fetch($sql, ['id' => $id]);
    }

    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO {$this->table} 
            (nombre, email, password, rol, telefono)
            VALUES (:nombre, :email, :password, :rol, :telefono)
        ";

        return $this->execute($sql, [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password' => $data['password'], // ya hasheado
            'rol' => $data['rol'] ?? 'particular',
            'telefono' => $data['telefono']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        if (isset($data['nombre'])) {
            $fields[] = "nombre = :nombre";
            $params['nombre'] = $data['nombre'];
        }

        if (isset($data['email'])) {
            $fields[] = "email = :email";
            $params['email'] = $data['email'];
        }

        if (isset($data['telefono'])) {
            $fields[] = "telefono = :telefono";
            $params['telefono'] = $data['telefono'];
        }

        if (isset($data['password'])) {
            $fields[] = "password = :password";
            $params['password'] = $data['password'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";

        return $this->execute($sql, $params);
    }
}