<?php

class CustomerRepository
{
    protected string $table = 'customers';

    // =========================
    // LIST
    // =========================
    public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT *
                FROM {$this->table}
                WHERE 1=1";

        $params = [];

        // =========================
        // KEYWORD (FIX HY093)
        // =========================
        $keyword = $conditions['keyword'] ?? null;

        if (is_string($keyword) && trim($keyword) !== '') {
            $keyword = '%' . trim($keyword) . '%';

            $sql .= ' AND (
                        name LIKE :kw1
                        OR phone LIKE :kw2
                        OR email LIKE :kw3
                    )';

            $params['kw1'] = $keyword;
            $params['kw2'] = $keyword;
            $params['kw3'] = $keyword;
        }

        // =========================
        // GROUP FILTER
        // =========================
        if (!empty($conditions['group_id'])) {
            $sql .= ' AND group_id = :group_id';
            $params['group_id'] = (int) $conditions['group_id'];
        }

        $sql .= ' ORDER BY id DESC';

        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return Database::get($sql, $params);
    }

    // =========================
    // FIND BY ID
    // =========================
    public function findById(int $id): ?array
    {
        return Database::first(
            "SELECT *
             FROM {$this->table}
             WHERE id = :id
             LIMIT 1",
            ['id' => $id],
        );
    }

    // =========================
    // CREATE (SAFE)
    // =========================
    public function create(array $data): int
    {
        $allowed = ['name', 'group_id', 'phone', 'address', 'description', 'email', 'created_at', 'updated_at'];

        $data = array_intersect_key($data, array_flip($allowed));

        foreach ($data as $k => $v) {
            if (is_array($v) || is_object($v)) {
                unset($data[$k]);
            }
        }

        $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
        $data['updated_at'] = $data['updated_at'] ?? date('Y-m-d H:i:s');

        if (empty($data)) {
            return 0;
        }

        $fields = array_keys($data);

        $columns = implode(',', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "INSERT INTO {$this->table} ({$columns})
                VALUES ({$placeholders})";

        return Database::insert($sql, $data);
    }

    // =========================
    // UPDATE (FIXED HY093 SAFE)
    // =========================
    public function updateById(int $id, array $data): int
    {
        $allowed = ['name', 'group_id', 'phone', 'address', 'description', 'email', 'updated_at'];

        $data = array_intersect_key($data, array_flip($allowed));

        foreach ($data as $k => $v) {
            if (is_array($v) || is_object($v)) {
                unset($data[$k]);
            }
        }

        if (empty($data)) {
            return 0;
        }

        $set = [];
        $clean = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
            $clean[$key] = $value;
        }

        $clean['id'] = $id;

        $sql =
            "UPDATE {$this->table}
                SET " .
            implode(', ', $set) .
            '
                WHERE id = :id';

        return Database::update($sql, $clean);
    }

    // =========================
    // DELETE
    // =========================
    public function deleteById(int $id): int
    {
        return Database::delete(
            "DELETE FROM {$this->table}
             WHERE id = :id",
            ['id' => $id],
        );
    }

    // =========================
    // COUNT (FIX HY093)
    // =========================
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total
                FROM {$this->table}
                WHERE 1=1";

        $params = [];

        $keyword = $conditions['keyword'] ?? null;

        if (is_string($keyword) && trim($keyword) !== '') {
            $keyword = '%' . trim($keyword) . '%';

            $sql .= ' AND (
                        name LIKE :kw1
                        OR phone LIKE :kw2
                        OR email LIKE :kw3
                    )';

            $params['kw1'] = $keyword;
            $params['kw2'] = $keyword;
            $params['kw3'] = $keyword;
        }

        if (!empty($conditions['group_id'])) {
            $sql .= ' AND group_id = :group_id';
            $params['group_id'] = (int) $conditions['group_id'];
        }

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }
}
