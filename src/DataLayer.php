
<?php

namespace Kreatept\DBLayer;

use PDO;

class DataLayer
{
    protected $table;
    protected $primaryKey = 'id';
    protected $timestamps = true;
    protected $softDeletes = true;
    protected $joins = [];
    protected $conditions = [];
    protected $params = [];
    protected $pdo;

    public function __construct(string $table)
    {
        $this->table = $table;
        $this->pdo = Database::getInstance();
    }

    public function create(array $data)
    {
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = $data['created_at'];
        }

        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function find($value, string $field = null): ?self
    {
        $field = $field ?? $this->primaryKey;
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = :value LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['value' => $value]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        return $record ? $this->hydrate($record) : null;
    }

    public function update(array $data)
    {
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $fields = implode(', ', array_map(fn($key) => "{$key} = :{$key}", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$fields} WHERE {$this->primaryKey} = :id";

        $data['id'] = $this->{$this->primaryKey};
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete()
    {
        if ($this->softDeletes) {
            return $this->update(['deleted_at' => date('Y-m-d H:i:s')]);
        }

        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $this->{$this->primaryKey}]);
    }

    public function restore()
    {
        if ($this->softDeletes) {
            return $this->update(['deleted_at' => null]);
        }
    }

    public function join(string $table, string $on, string $type = 'INNER'): self
    {
        $this->joins[] = "{$type} JOIN {$table} ON {$on}";
        return $this;
    }

    public function where(string $field, $operator, $value): self
    {
        $this->conditions[] = "{$field} {$operator} ?";
        $this->params[] = $value;
        return $this;
    }

    public function whereIn(string $field, array $values): self
    {
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $this->conditions[] = "{$field} IN ({$placeholders})";
        $this->params = array_merge($this->params, $values);
        return $this;
    }

    public function whereBetween(string $field, $start, $end): self
    {
        $this->conditions[] = "{$field} BETWEEN ? AND ?";
        $this->params[] = $start;
        $this->params[] = $end;
        return $this;
    }

    private function hydrate(array $data): self
    {
        $instance = new self($this->table);
        foreach ($data as $key => $value) {
            $instance->$key = $value;
        }
        return $instance;
    }
}

?>
