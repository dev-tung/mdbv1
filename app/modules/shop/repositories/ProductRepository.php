<?php

class ProductRepository extends Repository
{
    protected string $table = 'products';

    // =========================
    // BASE SELECT
    // =========================
    private function baseSelect(): string
    {
        return '
            SELECT p.*
            FROM products p
            WHERE 1=1
        ';
    }

    // =========================
    // APPLY FILTERS (REUSE)
    // =========================
    private function applyFilters(string &$sql, array &$params, array $conditions): void
    {
        // keyword search
        if (!empty($conditions['keyword'])) {
            $sql .= ' AND p.name LIKE :keyword';
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        // category
        if (!empty($conditions['category_id'])) {
            $sql .= ' AND p.category_id = :category_id';
            $params['category_id'] = $conditions['category_id'];
        }

        // status
        if (isset($conditions['status']) && $conditions['status'] !== '') {
            $sql .= ' AND p.status = :status';
            $params['status'] = $conditions['status'];
        }

        // price filter (optional range)
        if (!empty($conditions['price_min'])) {
            $sql .= ' AND p.price >= :price_min';
            $params['price_min'] = $conditions['price_min'];
        }

        if (!empty($conditions['price_max'])) {
            $sql .= ' AND p.price <= :price_max';
            $params['price_max'] = $conditions['price_max'];
        }

        // brand filter (if you have product_brands or JSON/column)
        if (!empty($conditions['brands']) && is_array($conditions['brands'])) {
            $placeholders = [];

            foreach ($conditions['brands'] as $index => $brandId) {
                $key = ":brand_$index";
                $placeholders[] = $key;
                $params[$key] = $brandId;
            }

            if (!empty($placeholders)) {
                $sql .= ' AND p.brand_id IN (' . implode(',', $placeholders) . ')';
            }
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

        $sql .= ' ORDER BY p.id DESC';

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
            FROM products p
            WHERE 1=1
        ';

        $params = [];

        $this->applyFilters($sql, $params, $conditions);

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }
}
