<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Model.php';

class Tecnico extends Model
{
    protected string $table = 'tecnicos';

    public function getAll(): array
    {
        $sql = "SELECT 
                    t.id,
                    t.nombre_completo,
                    t.especialidad_id,
                    t.disponible,
                    e.nombre_especialidad
                FROM tecnicos t
                LEFT JOIN especialidades e ON t.especialidad_id = e.id";

        return $this->fetchAll($sql);
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO tecnicos (nombre_completo, especialidad_id, disponible)
                VALUES (:nombre, :especialidad_id, 1)";

        return $this->execute($sql, [
            'nombre' => $data['nombre_completo'],
            'especialidad_id' => $data['especialidad_id']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE tecnicos 
                SET especialidad_id = :especialidad_id,
                    disponible = :disponible
                WHERE id = :id";

        return $this->execute($sql, [
            'id' => $id,
            'especialidad_id' => $data['especialidad_id'],
            'disponible' => $data['disponible']
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM tecnicos WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }
}