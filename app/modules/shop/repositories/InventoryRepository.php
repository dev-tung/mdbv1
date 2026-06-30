<?php

class InventoryRepository extends Repository
{
    // =========================
    // LIST
    // =========================
    public function getList(array $filters = []): array
    {
        $sql = "
            SELECT
                p.*,
                COALESCE(i.stock_in, 0)  AS stock_in,
                COALESCE(i.stock_out, 0) AS stock_out,
                COALESCE(i.stock, 0)     AS stock
            FROM products p
            LEFT JOIN inventories i
                ON i.product_id = p.id
            WHERE 1 = 1
        ";

        $params = [];

        if (!empty($filters['keyword'])) {
            $sql .= " AND p.name LIKE :keyword";
            $params['keyword'] = '%' . $filters['keyword'] . '%';
        }

        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $filters['category_id'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND p.status = :status";
            $params['status'] = $filters['status'];
        }

        // chỉ lấy sản phẩm còn tồn kho
        if (!empty($filters['stock'])) {
            $sql .= " AND COALESCE(i.stock, 0) > 0";
        }

        $sql .= " ORDER BY p.id DESC";

        return Database::get($sql, $params);
    }

    // =========================
    // UPDATE STOCK
    // =========================
    public function update(array $productIds): void
    {
        $productIds = array_unique(array_map('intval', $productIds));

        if (empty($productIds)) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        Database::query("
            INSERT INTO inventories (
                product_id,
                stock_in,
                stock_out,
                stock,
                updated_at
            )
            SELECT
                t.product_id,
                t.stock_in,
                t.stock_out,
                t.stock_in - t.stock_out,
                NOW()
            FROM (
                SELECT
                    product_id,

                    COALESCE(
                        SUM(CASE WHEN type = 'in' THEN quantity END),
                        0
                    ) AS stock_in,

                    COALESCE(
                        SUM(CASE WHEN type = 'out' THEN quantity END),
                        0
                    ) AS stock_out

                FROM inventory_transactions
                WHERE product_id IN ($placeholders)
                GROUP BY product_id
            ) t

            ON DUPLICATE KEY UPDATE
                stock_in  = VALUES(stock_in),
                stock_out = VALUES(stock_out),
                stock     = VALUES(stock),
                updated_at = NOW()
        ", $productIds);
    }
}