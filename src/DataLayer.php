<?php

namespace Kreatept\DBLayer;

use PDO;
use Exception;

class DataLayer
{
    protected $table;
    protected $primary;
    protected $pdo;
    protected $data = [];

    public function __construct(string $table, string $primary = 'id')
    {
        $this->table = $table;
        $this->primary = $primary;
        $this->pdo = Database::getInstance();
    }

    public function find($value, string $field = null): ?self
    {
        $field = $field ?? $this->primary;
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = :value LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['value' => $value]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record) {
            $this->data = $record;
            return $this;
        }

        return null;
    }

    public function update(array $data): bool
    {
        if (empty($this->data)) {
            throw new Exception("No record loaded to update.");
        }

        $setClause = implode(', ', array_map(fn($col) => "{$col} = :{$col}", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primary} = :primaryKey";

        $stmt = $this->pdo->prepare($sql);
        $data['primaryKey'] = $this->data[$this->primary];
        return $stmt->execute($data);
    }

    public function insert(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return (int)$this->pdo->lastInsertId();
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
