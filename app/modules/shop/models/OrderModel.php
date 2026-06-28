<?php

class OrderModel
{
    /**
     * BUILD WHERE
     */
    private function buildWhere(array $conditions, array &$params): string
    {
        $sql = " WHERE 1=1";

        if (!empty($conditions['keyword'])) {
            $sql .= " AND o.id LIKE :keyword";
            $params['keyword'] = '%' . trim($conditions['keyword']) . '%';
        }

        if (!empty($conditions['customer_id'])) {
            $sql .= " AND o.customer_id = :customer_id";
            $params['customer_id'] = $conditions['customer_id'];
        }

        if (!empty($conditions['status'])) {
            $sql .= " AND o.status = :status";
            $params['status'] = $conditions['status'];
        }

        if (!empty($conditions['payment'])) {
            $sql .= " AND o.payment = :payment";
            $params['payment'] = $conditions['payment'];
        }

        return $sql;
    }

    /**
     * LIST
     */
    public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $params = [];

        $sql = "
            SELECT
                o.*,
                c.name AS customer_name
            FROM orders o
            LEFT JOIN customers c
                ON c.id = o.customer_id
        ";

        $sql .= $this->buildWhere($conditions, $params);

        $sql .= " ORDER BY o.id DESC";

        if ($limit > 0) {
            $limit = (int) $limit;
            $offset = (int) $offset;

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
            SELECT COUNT(*) AS total
            FROM orders o
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
            "
                SELECT
                    o.*,
                    c.name AS customer_name
                FROM orders o
                LEFT JOIN customers c
                    ON c.id = o.customer_id
                WHERE o.id = :id
                LIMIT 1
            ",
            ['id' => $id]
        );
    }

    /**
     * CREATE
     */
    public function create(array $data): int
    {
        $fields = array_keys($data);

        $columns = implode(', ', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "
            INSERT INTO orders ({$columns})
            VALUES ({$placeholders})
        ";

        return Database::insert($sql, $data);
    }

    /**
     * UPDATE
     */
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

        $sql = "
            UPDATE orders
            SET " . implode(', ', $set) . "
            WHERE id = :id
        ";

        return Database::update($sql, $data);
    }

    /**
     * DELETE
     */
    public function deleteById(int $id): int
    {
        return Database::delete(
            "
                DELETE FROM orders
                WHERE id = :id
            ",
            ['id' => $id]
        );
    }

    /**
     * REVENUE REPORT
     */
    public function getRevenueReport(
        ?string $dateFrom,
        ?string $dateTo,
        int $limit,
        int $offset
    ): array {
        $limit = (int) $limit;
        $offset = (int) $offset;

        $sql = "
            SELECT
                DATE(o.created_at) AS date,
                COUNT(DISTINCT o.id) AS orders,

                SUM(oi.quantity * oi.price) AS revenue,

                (
                    SUM(oi.quantity * oi.price)
                    -
                    COALESCE(SUM(
                        oi.quantity * (
                            SELECT AVG(pp.unit_price)
                            FROM purchase_items pp
                            WHERE pp.product_id = oi.product_id
                        )
                    ), 0)
                ) AS profit

            FROM orders o

            JOIN order_items oi
                ON oi.order_id = o.id

            WHERE 1=1
        ";

        $params = [];

        if ($dateFrom) {
            $sql .= " AND o.created_at >= :date_from";
            $params['date_from'] = $dateFrom;
        }

        if ($dateTo) {
            $sql .= " AND o.created_at <= :date_to";
            $params['date_to'] = $dateTo;
        }

        $sql .= "
            GROUP BY DATE(o.created_at)
            ORDER BY DATE(o.created_at) DESC
            LIMIT {$limit} OFFSET {$offset}
        ";

        return Database::get($sql, $params);
    }

    /**
     * COUNT REVENUE REPORT
     */
    public function countRevenueReport(
        ?string $dateFrom,
        ?string $dateTo
    ): int {
        $sql = "
            SELECT COUNT(DISTINCT DATE(o.created_at)) AS total
            FROM orders o
            WHERE 1=1
        ";

        $params = [];

        if ($dateFrom) {
            $sql .= " AND DATE(o.created_at) >= :date_from";
            $params['date_from'] = $dateFrom;
        }

        if ($dateTo) {
            $sql .= " AND DATE(o.created_at) <= :date_to";
            $params['date_to'] = $dateTo;
        }

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }

    /**
     * SUM PROFIT REPORT
     */
    public function sumRevenueReport(
        ?string $dateFrom = null,
        ?string $dateTo = null
    ): float {
        $sql = "
            SELECT
                COALESCE(SUM(oi.quantity * oi.price), 0)
                -
                COALESCE(SUM(
                    oi.quantity * (
                        SELECT AVG(pp.unit_price)
                        FROM purchase_items pp
                        WHERE pp.product_id = oi.product_id
                    )
                ), 0)
                AS total_profit

            FROM order_items oi

            JOIN orders o
                ON o.id = oi.order_id

            WHERE 1=1
        ";

        $params = [];

        if ($dateFrom) {
            $sql .= " AND o.created_at >= :date_from";
            $params['date_from'] = $dateFrom;
        }

        if ($dateTo) {
            $sql .= " AND o.created_at <= :date_to";
            $params['date_to'] = $dateTo;
        }

        $row = Database::first($sql, $params);

        return (float) ($row['total_profit'] ?? 0);
    }
}