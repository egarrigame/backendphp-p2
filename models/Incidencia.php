<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Model.php';

class Incidencia extends Model
{
    protected string $table = 'incidencias';

    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table}
            (localizador, cliente_id, especialidad_id, estado_id, descripcion, direccion, fecha_servicio, tipo_urgencia)
            VALUES (:localizador, :cliente_id, :especialidad_id, :estado_id, :descripcion, :direccion, :fecha_servicio, :tipo_urgencia)";

        return $this->execute($sql, [
            'localizador' => 'REP-' . date('Y') . '-' . rand(1000, 9999),
            'cliente_id' => $data['cliente_id'],
            'especialidad_id' => $data['especialidad_id'],
            'estado_id' => 1, // Pendiente
            'descripcion' => $data['descripcion'],
            'direccion' => $data['direccion'],
            'fecha_servicio' => $data['fecha_servicio'],
            'tipo_urgencia' => $data['tipo_urgencia']
        ]);
    }

    public function findByCliente(int $clienteId): array
    {
        $sql = "SELECT i.*, e.nombre_estado, s.nombre_especialidad
                FROM incidencias i
                JOIN estados e ON i.estado_id = e.id
                JOIN especialidades s ON i.especialidad_id = s.id
                WHERE i.cliente_id = :cliente_id
                ORDER BY i.fecha_servicio DESC";

        return $this->fetchAll($sql, [
            'cliente_id' => $clienteId
        ]);
    }

    public function cancel(int $id, int $clienteId): bool
    {
        $sql = "UPDATE incidencias 
                SET estado_id = 4 
                WHERE id = :id AND cliente_id = :cliente_id";

        return $this->execute($sql, [
            'id' => $id,
            'cliente_id' => $clienteId
        ]);
    }

    public function findAll(): array
{
    $sql = "SELECT i.*, 
                   u.nombre AS cliente_nombre,
                   t.nombre_completo AS tecnico_nombre,
                   e.nombre_estado,
                   s.nombre_especialidad
            FROM incidencias i
            JOIN usuarios u ON i.cliente_id = u.id
            LEFT JOIN tecnicos t ON i.tecnico_id = t.id
            JOIN estados e ON i.estado_id = e.id
            JOIN especialidades s ON i.especialidad_id = s.id
            ORDER BY i.fecha_servicio DESC";

    return $this->fetchAll($sql);
}

        public function assignTecnico(int $incidenciaId, int $tecnicoId): bool
        {
            $sql = "UPDATE incidencias 
                    SET tecnico_id = :tecnico_id, estado_id = 2 
                    WHERE id = :id";

            return $this->execute($sql, [
                'tecnico_id' => $tecnicoId,
                'id' => $incidenciaId
            ]);
        }

        public function findById(int $id): array|false
{
    $sql = "SELECT i.*, u.nombre AS cliente_nombre
            FROM incidencias i
            JOIN usuarios u ON i.cliente_id = u.id
            WHERE i.id = :id";

    return $this->fetch($sql, ['id' => $id]);
}

public function updateAdmin(int $id, array $data): bool
{
    $sql = "UPDATE incidencias SET
                especialidad_id = :especialidad_id,
                estado_id = :estado_id,
                descripcion = :descripcion,
                direccion = :direccion,
                fecha_servicio = :fecha_servicio,
                tipo_urgencia = :tipo_urgencia
            WHERE id = :id";

    return $this->execute($sql, [
        'id' => $id,
        'especialidad_id' => $data['especialidad_id'],
        'estado_id' => $data['estado_id'],
        'descripcion' => $data['descripcion'],
        'direccion' => $data['direccion'],
        'fecha_servicio' => $data['fecha_servicio'],
        'tipo_urgencia' => $data['tipo_urgencia']
    ]);
}
}