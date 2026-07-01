<?php

class InventoryRepository extends Repository
{

    // =========================
    // PRODUCTS WITH STOCK
    // =========================
    public function getList(array $filters = []): array
    {
        $sql = "
            SELECT
                p.`name`,
                COALESCE(i.stock_in, 0)  AS stock_in,
                COALESCE(i.stock_out, 0) AS stock_out,
                COALESCE(i.stock, 0)     AS stock
            FROM products p
            JOIN inventories i
                ON i.product_id = p.id
            WHERE 1 = 1
        ";

        $params = [];

        if (!empty($filters['keyword'])) {
            $sql .= " AND p.name LIKE :keyword";
            $params['keyword'] = '%' . $filters['keyword'] . '%';
        }

        $sql .= " ORDER BY p.id DESC";

        return Database::get($sql, $params);
    }


    // =========================
    // PRODUCTS FOR SALES
    // =========================
    public function getStock(array $filters = []): array
    {
        $sql = "
            SELECT
                p.*,
                COALESCE(i.stock_in, 0)  AS stock_in,
                COALESCE(i.stock_out, 0) AS stock_out,
                COALESCE(i.stock, 0)     AS stock,
                    i.purchase_item_id
            FROM products p
            JOIN inventories i
                ON i.product_id = p.id
            JOIN purchase_items pi
                    ON pi.id = i.purchase_item_id
            JOIN purchases pu 
                    ON pu.id = pi.purchase_id
            WHERE COALESCE(i.stock, 0) > 0
			AND pu.status = 'received'
        ";

        $params = [];

        // keyword filter (product level)
        if (!empty($filters['keyword'])) {
            $sql .= " AND p.name LIKE :keyword";
            $params['keyword'] = '%' . $filters['keyword'] . '%';
        }

        $sql .= " ORDER BY p.id DESC";

        return Database::get($sql, $params);
    }

    // =========================
    // UPDATE STOCK
    // =========================
    public function update(array $productIds): void
    {
        $productIds = array_values(array_unique(array_map('intval', $productIds)));

        if (empty($productIds)) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        Database::query("
            INSERT INTO inventories (
                purchase_item_id,
                product_id,
                stock_in,
                stock_out,
                stock,
                created_at,
                updated_at
            )
            SELECT
                t.purchase_item_id,
                t.product_id,
                t.stock_in,
                t.stock_out,
                t.stock_in - t.stock_out,
                NOW(),
                NOW()
            FROM (
                SELECT
                    purchase_item_id,
                    product_id,

                    SUM(CASE WHEN type = 'in' THEN quantity ELSE 0 END) AS stock_in,
                    SUM(CASE WHEN type = 'out' THEN quantity ELSE 0 END) AS stock_out

                FROM inventory_transactions
                WHERE product_id IN ($placeholders)

                GROUP BY purchase_item_id, product_id
            ) t

            ON DUPLICATE KEY UPDATE
                stock_in   = VALUES(stock_in),
                stock_out  = VALUES(stock_out),
                stock      = VALUES(stock),
                updated_at = NOW()
        ", $productIds);
    }
}