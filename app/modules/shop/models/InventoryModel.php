<?php

class InventoryModel
{
    /**
     * Lấy toàn bộ sản phẩm kèm tồn kho
     * Bao gồm sản phẩm chưa có tồn
     */
    public function getList(array $filters = [])
    {
        $sql = "
            SELECT
                p.*,
                COALESCE(i.stock_in, 0) AS stock_in,
                COALESCE(i.stock_out, 0) AS stock_out,
                COALESCE(i.stock, 0) AS stock
            FROM products p
            LEFT JOIN inventories i ON i.product_id = p.id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filters['keyword'])) {
            $sql .= " AND p.name LIKE ?";
            $params[] = "%{$filters['keyword']}%";
        }

        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category_id'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND p.status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY p.id DESC";

        return Database::get($sql, $params);
    }

    /**
     * Lấy sản phẩm còn tồn kho
     */
    public function getStock(array $filters = [])
    {
        $sql = "
            SELECT
                p.*,
                i.stock_in,
                i.stock_out,
                i.stock
            FROM inventories i
            JOIN products p ON p.id = i.product_id
            WHERE i.stock > 0
        ";

        $params = [];

        if (!empty($filters['keyword'])) {
            $sql .= " AND p.name LIKE ?";
            $params[] = "%{$filters['keyword']}%";
        }

        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category_id'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND p.status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY p.name ASC";

        return Database::get($sql, $params);
    }

    /**
     * Cập nhật tồn kho của một sản phẩm
     */
    public function updateStock(int $productId)
    {
        $row = Database::getOne("
            SELECT
                COALESCE(SUM(CASE WHEN type='in' THEN quantity END),0) AS stock_in,
                COALESCE(SUM(CASE WHEN type='out' THEN quantity END),0) AS stock_out
            FROM inventory_transactions
            WHERE product_id = ?
        ", [$productId]);

        $stockIn  = (int)$row['stock_in'];
        $stockOut = (int)$row['stock_out'];
        $stock    = $stockIn - $stockOut;

        $exists = Database::getOne("
            SELECT id
            FROM inventories
            WHERE product_id = ?
        ", [$productId]);

        if ($exists) {
            Database::update("
                UPDATE inventories
                SET
                    stock_in = ?,
                    stock_out = ?,
                    stock = ?,
                    updated_at = NOW()
                WHERE product_id = ?
            ", [
                $stockIn,
                $stockOut,
                $stock,
                $productId
            ]);
        } else {
            Database::insert("
                INSERT INTO inventories
                (
                    product_id,
                    stock_in,
                    stock_out,
                    stock
                )
                VALUES (?,?,?,?)
            ", [
                $productId,
                $stockIn,
                $stockOut,
                $stock
            ]);
        }
    }
}