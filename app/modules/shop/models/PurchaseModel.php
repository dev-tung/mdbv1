<?php

class PurchaseModel
{
    private PurchaseItemModel $itemModel;
    private InventoryTransactionModel $inventoryModel;

    public function __construct()
    {
        $this->itemModel = new PurchaseItemModel();
        $this->inventoryModel = new InventoryTransactionModel();
    }

    // =========================
    // GET LIST
    // =========================
    public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $sql = "
            SELECT p.*,
                   s.name AS supplier_name,
                   w.name AS warehouse_name
            FROM purchases p
            LEFT JOIN suppliers s ON s.id = p.supplier_id
            LEFT JOIN warehouses w ON w.id = p.warehouse_id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($conditions['keyword'])) {
            $sql .= " AND p.code LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

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
        $sql = "SELECT COUNT(*) total FROM purchases WHERE 1=1";
        $params = [];

        if (!empty($conditions['keyword'])) {
            $sql .= " AND code LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        if (!empty($conditions['supplier_id'])) {
            $sql .= " AND supplier_id = :supplier_id";
            $params['supplier_id'] = $conditions['supplier_id'];
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
    // FIND BY ID
    // =========================
    public function findById(int $id): ?array
    {
        return Database::first(
            "SELECT * FROM purchases WHERE id = :id",
            ['id' => $id]
        );
    }

    // =========================
    // CREATE PURCHASE
    // =========================
    public function createPurchase(array $input): int
    {
        return Database::transaction(function () use ($input) {

            $purchaseId = Database::create('purchases', [
                'supplier_id'  => (int)$input['supplier_id'],
                'warehouse_id' => (int)$input['warehouse_id'],
                'status'       => $input['status'] ?? 'draft',
                'payment'      => $input['payment'] ?? '',
                'description'  => trim($input['description'] ?? ''),
                'total_cost'   => 0
            ]);

            $total = 0;
            $items = [];
            $logs  = [];

            foreach ($input['products'] as $p) {

                $productId = (int)($p['product_id'] ?? $p['id'] ?? 0);
                $qty       = (int)($p['quantity'] ?? 1);
                $price     = (float)($p['price'] ?? 0);

                $total += $qty * $price;

                $items[] = [
                    'purchase_id' => $purchaseId,
                    'product_id'  => $productId,
                    'quantity'    => $qty,
                    'unit_price'  => $price
                ];

                $logs[] = [
                    'product_id'     => $productId,
                    'warehouse_id'   => (int)$input['warehouse_id'],
                    'type'           => 'in',
                    'quantity'       => $qty,
                    'reference_type' => 'purchase',
                    'reference_id'   => $purchaseId,
                    'note'           => 'import purchase'
                ];
            }

            $this->itemModel->insertBatch($items);
            $this->inventoryModel->insertBatch($logs);

            Database::updateById('purchases', $purchaseId, [
                'total_cost' => $total
            ]);

            return $purchaseId;
        });
    }

    // =========================
    // UPDATE PURCHASE
    // =========================
    public function updatePurchase(array $input): int
    {
        return Database::transaction(function () use ($input) {

            $id = (int)$input['id'];

            $old = $this->findById($id);

            if (!$old) {
                throw new Exception('Purchase not found');
            }

            $oldItems = $this->itemModel->getByPurchaseId($id);

            Database::updateById('purchases', $id, [
                'supplier_id'  => (int)$input['supplier_id'],
                'warehouse_id' => (int)$input['warehouse_id'],
                'status'       => $input['status'] ?? '',
                'payment'      => $input['payment'] ?? '',
                'description'  => trim($input['description'] ?? '')
            ]);

            $rollback = [];

            foreach ($oldItems as $item) {
                $rollback[] = [
                    'product_id'     => $item['product_id'],
                    'warehouse_id'   => $old['warehouse_id'],
                    'type'           => 'out',
                    'quantity'       => $item['quantity'],
                    'reference_type' => 'purchase_update_old',
                    'reference_id'   => $id,
                    'note'           => 'rollback'
                ];
            }

            $this->inventoryModel->insertBatch($rollback);

            $this->itemModel->deleteByPurchaseId($id);

            $items = [];
            $logs  = [];
            $total = 0;

            foreach ($input['products'] as $p) {

                $productId = (int)($p['product_id'] ?? $p['id']);
                $qty       = (int)$p['quantity'];
                $price     = (float)$p['price'];

                $total += $qty * $price;

                $items[] = [
                    'purchase_id' => $id,
                    'product_id'  => $productId,
                    'quantity'    => $qty,
                    'unit_price'  => $price
                ];

                $logs[] = [
                    'product_id'     => $productId,
                    'warehouse_id'   => (int)$input['warehouse_id'],
                    'type'           => 'in',
                    'quantity'       => $qty,
                    'reference_type' => 'purchase_update',
                    'reference_id'   => $id,
                    'note'           => 'update purchase'
                ];
            }

            $this->itemModel->insertBatch($items);
            $this->inventoryModel->insertBatch($logs);

            Database::updateById('purchases', $id, [
                'total_cost' => $total
            ]);

            return $id;
        });
    }

    // =========================
    // DELETE PURCHASE
    // =========================
    public function deletePurchase(int $id): int
    {
        return Database::transaction(function () use ($id) {

            $purchase = $this->findById($id);

            if (!$purchase) {
                throw new Exception('Purchase not found');
            }

            $items = $this->itemModel->getByPurchaseId($id);

            $logs = [];

            foreach ($items as $item) {
                $logs[] = [
                    'product_id'     => $item['product_id'],
                    'warehouse_id'   => $purchase['warehouse_id'],
                    'type'           => 'out',
                    'quantity'       => $item['quantity'],
                    'reference_type' => 'purchase_delete',
                    'reference_id'   => $id,
                    'note'           => 'delete rollback'
                ];
            }

            $this->inventoryModel->insertBatch($logs);

            $this->itemModel->deleteByPurchaseId($id);

            return Database::deleteById('purchases', $id);
        });
    }
}