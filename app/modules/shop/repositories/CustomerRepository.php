<?php

class CustomerRepository
{
    protected string $table = 'customers';

    /**
     * Lấy danh sách customer
     */
    public function getList(array $conditions = []): array
    {
        $sql = '
            SELECT
                c.*,
                g.name AS group_name
            FROM customers c
            LEFT JOIN customer_groups g
                ON g.id = c.group_id
            WHERE 1=1
        ';

        $params = [];

        // KEYWORD SEARCH
        if (!empty($conditions['keyword'])) {
            $sql .= '
                AND (
                    c.name LIKE :name
                    OR c.phone LIKE :phone
                    OR c.email LIKE :email
                )
            ';

            $keyword = '%' . $conditions['keyword'] . '%';

            $params['name'] = $keyword;
            $params['phone'] = $keyword;
            $params['email'] = $keyword;
        }

        $sql .= ' ORDER BY c.id DESC';

        return Database::get($sql, $params);
    }

    /**
     * Đếm customer (pagination)
     */
    public function count(array $conditions = []): int
    {
        $sql = '
            SELECT COUNT(*) AS total
            FROM customers c
            LEFT JOIN customer_groups g
                ON g.id = c.group_id
            WHERE 1=1
        ';

        $params = [];

        // KEYWORD SEARCH
        if (!empty($conditions['keyword'])) {
            $sql .= '
                AND (
                    c.name LIKE :name
                    OR c.phone LIKE :phone
                    OR c.email LIKE :email
                )
            ';

            $keyword = '%' . $conditions['keyword'] . '%';

            $params['name'] = $keyword;
            $params['phone'] = $keyword;
            $params['email'] = $keyword;
        }

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }
}
