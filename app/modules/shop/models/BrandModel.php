<?php

class BrandModel
{
    protected string $table = 'brands';
    protected string $alias = 'b';

    /**
     * BUILD WHERE
     */
    private function buildWhere(array $conditions, array &$params): string
    {
        $sql = " WHERE 1=1 ";

        // STATUS
        if (isset($conditions['status']) && $conditions['status'] !== '') {
            $sql .= " AND {$this->alias}.status = :status";
            $params['status'] = $conditions['status'];
        }

        // KEYWORD (search name)
        if (!empty($conditions['keyword'])) {
            $sql .= " AND {$this->alias}.name LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        return $sql;
    }

    /**
     * GET LIST
     */
    public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $params = [];

        $sql = "
            SELECT 
                {$this->alias}.*
            FROM {$this->table} {$this->alias}
        ";

        $sql .= $this->buildWhere($conditions, $params);

        $sql .= " ORDER BY {$this->alias}.id DESC";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return Database::get($sql, $params);
    }

    /**
     * COUNT
     */
    public function count(array $conditions = []): int
    {
        $params = [];

        $sql = "
            SELECT COUNT(*) as total
            FROM {$this->table} {$this->alias}
        ";

        $sql .= $this->buildWhere($conditions, $params);

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }

    /**
     * FIND BY ID
     */
    public function findById(int $id): ?array
    {
        return Database::first(
            "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1",
            ['id' => $id]
        );
    }

    /**
     * CREATE
     */
    public function create(array $data): int
    {
        $fields = array_keys($data);

        $columns = implode(',', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        return Database::insert($sql, $data);
    }

    /**
     * UPDATE
     */
    public function updateById(int $id, array $data): int
    {
        $set = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }

        $data['id'] = $id;

        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE id = :id";

        return Database::update($sql, $data);
    }

    /**
     * DELETE
     */
    public function deleteById(int $id): int
    {
        return Database::delete(
            "DELETE FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }
}