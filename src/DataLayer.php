<?php

namespace Kreatept\DBLayer;

use PDO;
use Kreatept\DBLayer\Traits\QueryHelpers;

class DataLayer
{
    use QueryHelpers;

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
    protected $tables = [];
    protected $pdo;

    public function __construct(string $table, string $primary = 'id', array $required = [])
    {
        $this->table = $table;
        $this->primary = $primary;
        $this->required = $required;
        $this->pdo = Database::getInstance();
    }

    public function join(string $table, string $on, string $type = 'INNER'): self
    {
        $this->joins[] = "{$type} JOIN {$table} ON {$on}";
        return $this;
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
        $table = $this->tables ? implode(', ', $this->tables) : $this->table;
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
}
