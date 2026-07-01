<?php

class OrderRepository extends Repository
{
    protected string $table = 'orders';

    // =========================
    // BASE SELECT
    // =========================
    private function baseSelect(): string
    {
        return "
            SELECT
                p.*,
                c.name AS customer_name
            FROM orders p
            LEFT JOIN customers c
                ON c.id = p.customer_id
            WHERE 1=1
        ";
    }

    // =========================
    // BASE COUNT
    // =========================
    private function baseCount(): string
    {
        return "
            SELECT COUNT(*) AS total
            FROM orders p
            LEFT JOIN customers c
                ON c.id = p.customer_id
            WHERE 1=1
        ";
    }

    // =========================
    // APPLY FILTERS
    // =========================
    private function applyFilters(string &$sql, array &$params, array $conditions): void
    {
        // CUSTOMER
        if (!empty($conditions['keyword'])) {
            $sql .= " AND c.name LIKE :customer_name";
            $params['customer_name'] = '%' . trim($conditions['keyword']) . '%';
        }

        // STATUS
        if (!empty($conditions['status'])) {
            $sql .= " AND p.status = :status";
            $params['status'] = $conditions['status'];
        }

        // PAYMENT
        if (!empty($conditions['payment'])) {
            $sql .= " AND p.payment = :payment";
            $params['payment'] = $conditions['payment'];
        }

        // DATE FROM
        if (!empty($conditions['date_from'])) {
            $sql .= " AND DATE(p.created_at) >= :date_from";
            $params['date_from'] = $conditions['date_from'];
        }

        // DATE TO
        if (!empty($conditions['date_to'])) {
            $sql .= " AND DATE(p.created_at) <= :date_to";
            $params['date_to'] = $conditions['date_to'];
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

        $sql .= " ORDER BY p.id DESC";

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
        $sql = $this->baseCount();
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
        $sql = $this->baseSelect() . " AND p.id = :id LIMIT 1";

        return Database::first($sql, [
            'id' => $id
        ]);
    }
}