<?php

class OrderModel
{
    protected string $table = 'orders';

    // =========================
    // LIST
    // =========================
    public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT 
                    o.*,
                    c.name AS customer_name
                FROM {$this->table} o
                LEFT JOIN customers c ON c.id = o.customer_id
                WHERE 1=1";

        $params = [];

        if (!empty($conditions['keyword'])) {
            $sql .= " AND o.id LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
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

        $sql .= " ORDER BY o.id DESC";

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
            "SELECT o.*, c.name AS customer_name
            FROM {$this->table} o
            LEFT JOIN customers c ON c.id = o.customer_id
            WHERE o.id = :id
            LIMIT 1",
            ['id' => $id]
        );
    }

    // =========================
    // CREATE
    // =========================
    public function create(array $data): int
    {
        $fields = array_keys($data);

        $columns = implode(',', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "INSERT INTO {$this->table} ({$columns})
                VALUES ({$placeholders})";

        return Database::insert($sql, $data);
    }

    // =========================
    // UPDATE
    // =========================
    public function updateById(int $id, array $data): int
    {
        $set = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }

        $data['id'] = $id;

        $sql = "UPDATE {$this->table}
                SET " . implode(', ', $set) . "
                WHERE id = :id";

        return Database::update($sql, $data);
    }

    // =========================
    // DELETE
    // =========================
    public function deleteById(int $id): int
    {
        return Database::delete(
            "DELETE FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }

    // =========================
    // COUNT
    // =========================
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($conditions['keyword'])) {
            $sql .= " AND id LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        if (!empty($conditions['customer_id'])) {
            $sql .= " AND customer_id = :customer_id";
            $params['customer_id'] = $conditions['customer_id'];
        }

        if (!empty($conditions['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $conditions['status'];
        }

        if (!empty($conditions['payment'])) {
            $sql .= " AND payment = :payment";
            $params['payment'] = $conditions['payment'];
        }

        $row = Database::first($sql, $params);

        return (int)($row['total'] ?? 0);
    }

    // =========================
    // REVENUE REPORT (BY DATE)
    // =========================
    public function getRevenueReport($dateFrom, $dateTo, $limit, $offset)
    {
        $sql = "
            SELECT 
                DATE(o.created_at) as date,
                COUNT(DISTINCT o.id) as orders,

                SUM(oi.quantity * oi.price) as revenue,

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
                ) as profit

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
            LIMIT $limit OFFSET $offset
        ";

        return Database::get($sql, $params);
    }

    public function countRevenueReport(?string $dateFrom, ?string $dateTo): int
    {
        $sql = "
            SELECT COUNT(DISTINCT DATE(o.created_at)) as total
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

        return (int)($row['total'] ?? 0);
    }
    

    public function sumRevenueReport($dateFrom = null, $dateTo = null)
    {
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
            $sql .= " AND o.created_at >= :date_from ";
            $params['date_from'] = $dateFrom;
        }

        if ($dateTo) {
            $sql .= " AND o.created_at <= :date_to ";
            $params['date_to'] = $dateTo;
        }

        $row = Database::first($sql, $params);

        return (float)($row['total_profit'] ?? 0);
    }
}