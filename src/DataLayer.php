<?php

namespace Kreatept\DBLayer;

use PDO;
use Exception;

class DataLayer
{
    protected $table;
    protected $primary;
    protected $required;
    protected $joins = [];
    protected $fields = ["*"];
    protected $conditions = [];
    protected $groupBy = [];
    protected $having = [];
    protected $order = [];
    protected $limit;
    protected $offset;
    protected $params = [];
    protected $data = [];
    protected $pdo;

    public function __construct(string $table, string $primary = 'id', array $required = [])
    {
        $this->table = $table;
        $this->primary = $primary;
        $this->required = $required;
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

    public function insert(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return (int)$this->pdo->lastInsertId();
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

    public function destroy(): bool
    {
        if (empty($this->data)) {
            throw new Exception("No record loaded to delete.");
        }

        $sql = "DELETE FROM {$this->table} WHERE {$this->primary} = :primaryKey";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['primaryKey' => $this->data[$this->primary]]);
    }

    public function where(string $field, $value, string $operator = '=', string $logicalOperator = 'AND'): self
    {
        $paramKey = ":param_" . count($this->params);
        $this->conditions[] = "{$logicalOperator} {$field} {$operator} {$paramKey}";
        $this->params[$paramKey] = $value;
        return $this;
    }

    public function fetch(): array
    {
        $sql = $this->buildQuery();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buildQuery(): string
    {
        $table = $this->table;
        $fields = implode(', ', $this->fields);
        $joins = $this->joins ? ' ' . implode(' ', $this->joins) : '';
        $conditions = $this->conditions ? ' WHERE ' . ltrim(implode(' ', $this->conditions), 'AND ') : '';
        $groupBy = $this->groupBy ? ' GROUP BY ' . implode(', ', $this->groupBy) : '';
        $having = $this->having ? ' HAVING ' . implode(' AND ', $this->having) : '';
        $order = $this->order ? ' ORDER BY ' . implode(', ', $this->order) : '';
        $limit = $this->limit ? " LIMIT {$this->limit}" : '';
        $offset = $this->offset ? " OFFSET {$this->offset}" : '';

        return "SELECT {$fields} FROM {$table}{$joins}{$conditions}{$groupBy}{$having}{$order}{$limit}{$offset}";
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
