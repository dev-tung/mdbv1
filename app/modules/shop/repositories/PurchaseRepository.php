<?php

class PurchaseRepository extends Repository
{
    protected string $table = 'purchases';

    // =========================
    // BASE SELECT
    // =========================
    private function baseSelect(): string
    {
        return "
            SELECT p.*,
                   s.name AS supplier_name,
                   w.name AS warehouse_name
            FROM purchases p
            LEFT JOIN suppliers s ON s.id = p.supplier_id
            LEFT JOIN warehouses w ON w.id = p.warehouse_id
            WHERE 1=1
        ";
    }

    // =========================
    // APPLY FILTERS (REUSE)
    // =========================
    private function applyFilters(string &$sql, array &$params, array $conditions): void
    {

        if (!empty($conditions['supplier_id'])) {
            $sql .= " AND p.supplier_id = :supplier_id";
            $params['supplier_id'] = $conditions['supplier_id'];
        }

        if (!empty($conditions['status'])) {
            $sql .= " AND p.status = :status";
            $params['status'] = $conditions['status'];
        }

        if (!empty($conditions['payment'])) {
            $sql .= " AND p.payment = :payment";
            $params['payment'] = $conditions['payment'];
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
        $sql = "
            SELECT COUNT(*) AS total
            FROM purchases p
            WHERE 1=1
        ";

        $params = [];

        $this->applyFilters($sql, $params, $conditions);

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }

    public function findById(int $id): ?array
    {
        $sql = $this->baseSelect() . " AND p.id = :id LIMIT 1";

        return Database::first($sql, ['id' => $id]);
    }
}