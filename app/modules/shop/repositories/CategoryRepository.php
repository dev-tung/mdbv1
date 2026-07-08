<?php

class CategoryRepository extends Repository
{
    protected string $table = 'categories';

    // =========================
    // BASE SELECT
    // =========================
    private function baseSelect(): string
    {
        return '
            SELECT *
            FROM categories c
            WHERE 1=1
        ';
    }

    // =========================
    // APPLY FILTERS
    // =========================
    private function applyFilters(string &$sql, array &$params, array $conditions): void
    {
        // keyword
        if (!empty($conditions['keyword'])) {
            $sql .= ' AND c.name LIKE :keyword';
            $params['keyword'] = '%' . trim($conditions['keyword']) . '%';
        }
    }

    // =========================
    // LIST
    // =========================
    public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $sql = $this->baseSelect();
        $params = [];

        $this->applyFilters($sql, $params, $conditions);

        $sql .= ' ORDER BY c.id DESC';

        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return Database::get($sql, $params);
    }

    // =========================
    // COUNT
    // =========================
    public function count(array $conditions = []): int
    {
        $sql = '
            SELECT COUNT(*) AS total
            FROM categories c
            WHERE 1=1
        ';

        $params = [];

        $this->applyFilters($sql, $params, $conditions);

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }

    // =========================
    // FIND BY ID
    // =========================
    public function findById(int $id): ?array
    {
        return Database::first(
            '
                SELECT *
                FROM categories
                WHERE id = :id
                LIMIT 1
            ',
            ['id' => $id],
        );
    }

    // =========================
    // CREATE
    // =========================
    public function create(array $data): int
    {
        $fields = array_keys($data);

        $columns = implode(', ', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "
            INSERT INTO categories ({$columns})
            VALUES ({$placeholders})
        ";

        return Database::insert($sql, $data);
    }

    // =========================
    // UPDATE
    // =========================
    public function updateById(int $id, array $data): int
    {
        if (empty($data)) {
            return 0;
        }

        $set = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }

        $data['id'] = $id;

        $sql =
            '
            UPDATE categories
            SET ' .
            implode(', ', $set) .
            '
            WHERE id = :id
        ';

        return Database::update($sql, $data);
    }

    // =========================
    // DELETE
    // =========================
    public function deleteById(int $id): int
    {
        return Database::delete(
            '
                DELETE FROM categories
                WHERE id = :id
            ',
            ['id' => $id],
        );
    }
}
