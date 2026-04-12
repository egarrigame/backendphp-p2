<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Model.php';

class Especialidad extends Model
{
    protected string $table = 'especialidades';

    public function getAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->fetchAll($sql);
    }

    public function findById(int $id): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        return $this->fetch($sql, ['id' => $id]);
    }
}