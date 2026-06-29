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

        $sql .= " ORDER BY p.id DESC";

        return Database::get($sql, $params);
    }

    // =========================
    // STOCK
    // =========================
    public function getStock(array $filters = []): array
    {
        $sql = "
            SELECT
                p.*,
                i.stock_in,
                i.stock_out,
                i.stock
            FROM inventories i
            INNER JOIN products p
                ON p.id = i.product_id
            WHERE i.stock > 0
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

        $sql .= " ORDER BY p.name ASC";

        return Database::get($sql, $params);
    }

    // =========================
    // UPDATE STOCK
    // =========================
    public function updateStock(int $productId): void
    {
        $stock = Database::first(
            "
            SELECT
                COALESCE(SUM(CASE WHEN type = 'in' THEN quantity END), 0)  AS stock_in,
                COALESCE(SUM(CASE WHEN type = 'out' THEN quantity END), 0) AS stock_out
            FROM inventory_transactions
            WHERE product_id = :product_id
            ",
            [
                'product_id' => $productId
            ]
        );

        $stockIn  = (int) $stock['stock_in'];
        $stockOut = (int) $stock['stock_out'];
        $current  = $stockIn - $stockOut;

        $exists = Database::first(
            "
            SELECT id
            FROM inventories
            WHERE product_id = :product_id
            ",
            [
                'product_id' => $productId
            ]
        );

        if ($exists) {

            Database::query(
                "
                UPDATE inventories
                SET
                    stock_in = :stock_in,
                    stock_out = :stock_out,
                    stock = :stock,
                    updated_at = NOW()
                WHERE product_id = :product_id
                ",
                [
                    'stock_in'   => $stockIn,
                    'stock_out'  => $stockOut,
                    'stock'      => $current,
                    'product_id' => $productId,
                ]
            );

            return;
        }

        Database::query(
            "
            INSERT INTO inventories
            (
                product_id,
                stock_in,
                stock_out,
                stock
            )
            VALUES
            (
                :product_id,
                :stock_in,
                :stock_out,
                :stock
            )
            ",
            [
                'product_id' => $productId,
                'stock_in'   => $stockIn,
                'stock_out'  => $stockOut,
                'stock'      => $current,
            ]
        );
    }
}