<?php

class PurchaseService
{
    private PurchaseRepository $purchaseRepository;
    private PurchaseItemRepository $purchaseItemRepository;
    private InventoryTransactionRepository $inventoryTransactionRepository;
    private InventoryRepository $inventoryRepository;

    public function __construct()
    {
        $this->purchaseRepository             = new PurchaseRepository();
        $this->purchaseItemRepository         = new PurchaseItemRepository();
        $this->inventoryTransactionRepository = new InventoryTransactionRepository();
        $this->inventoryRepository            = new InventoryRepository();
    }

    // =========================
    // LIST PURCHASE
    // =========================
    public function getList(array $input): array
    {
        $page  = $input['page'] ?? 1;
        $limit = Config::get('pagination', 'default_per_page');
        $offset = ($page - 1) * $limit;

        $filters = [
            'keyword'     => $input['keyword'] ?? null,
            'supplier_id' => $input['supplier_id'] ?? null,
            'status'      => $input['status'] ?? null,
            'payment'     => $input['payment'] ?? null,
        ];

        $data  = $this->purchaseRepository->getList($filters, $limit, $offset);
        $total = $this->purchaseRepository->count($filters);

        return [
            'data' => $data,
            'meta' => [
                'page'       => (int)$page,
                'perPage'    => (int)$limit,
                'total'      => $total,
                'totalPages' => $limit > 0 ? (int)ceil($total / $limit) : 0,
            ]
        ];
    }


    // =========================
    // SHOW PURCHASE
    // =========================
    public function show(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $purchase = $this->purchaseRepository->findById($id);

        if (!$purchase) {
            return null;
        }

        $items = $this->purchaseItemRepository->getByPurchaseId($id);

        $products = [];

        foreach ($items as $item) {
            $products[] = [
                'product_id' => (int) $item['product_id'],
                'name'       => $item['product_name'] ?? '',
                'price'      => (float) $item['unit_price'],
                'quantity'   => (int) $item['quantity'],
                'subtotal'   => (float) $item['unit_price'] * (int) $item['quantity'],
            ];
        }

        return [
            'id'           => (int) $purchase['id'],
            'supplier_id'  => (int) $purchase['supplier_id'],
            'warehouse_id' => (int) $purchase['warehouse_id'],
            'status'       => $purchase['status'],
            'payment'      => $purchase['payment'],
            'description'  => $purchase['description'] ?? '',
            'supplier' => [
                'name' => $purchase['supplier_name'] ?? ''
            ],
            'total_amount' => (float) ($purchase['total_amount'] ?? 0),
            'paid_amount' => (float) ($purchase['paid_amount'] ?? 0),
            'debt_amount' => (float) ($purchase['debt_amount'] ?? 0),
            'products' => $products
        ];
    }

    // =========================
    // CREATE PURCHASE
    // =========================
    public function create(array $input): int
    {
        return Database::transaction(function () use ($input) {

            $purchaseId = $this->purchaseRepository->create([
                'supplier_id'  => $input['supplier_id'] ?? null,
                'warehouse_id' => $input['warehouse_id'] ?? null,
                'status'       => $input['status'] ?? 'draft',
                'payment'      => $input['payment'] ?? '',
                'description'  => $input['description'] ?? '',
                'total_amount' => 0,
                'paid_amount'  => $input['paid_amount'] ?? 0,
                'debt_amount'  => 0
            ]);

            $total = 0;
            $items = [];
            $logs  = [];

            foreach ($input['items'] ?? $input['products'] ?? [] as $p) {

                $productId = $p['product_id'] ?? $p['id'] ?? 0;
                $qty       = $p['quantity'] ?? 1;
                $price     = $p['price'] ?? 0;

                if ($productId <= 0 || $qty <= 0) {
                    continue;
                }

                $lineTotal = $qty * $price;
                $total += $lineTotal;

                $items[] = [
                    'purchase_id' => $purchaseId,
                    'product_id'  => $productId,
                    'quantity'    => $qty,
                    'unit_price'  => $price
                ];

                $logs[] = [
                    'product_id'     => $productId,
                    'warehouse_id'   => $input['warehouse_id'] ?? null,
                    'type'           => 'in',
                    'quantity'       => $qty,
                    'reference_type' => 'purchase',
                    'reference_id'   => $purchaseId,
                    'note'           => 'import purchase'
                ];
            }

            if (!empty($items)) {
                $this->purchaseItemRepository->createBatch($items);
            }

            if (!empty($logs)) {
                $this->inventoryTransactionRepository->createBatch($logs);

                $productIds = array_column($items, 'product_id');
                $this->inventoryRepository->update($productIds);
            }

            $debt = max($total - ($input['paid_amount'] ?? 0), 0);

            $this->purchaseRepository->updateById($purchaseId, [
                'total_amount'=> $total,
                'debt_amount' => $debt
            ]);

            return $purchaseId;
        });
    }

    // =========================
    // UPDATE PURCHASE
    // =========================
    public function update(array $input): int
    {
        return Database::transaction(function () use ($input) {

            $id = $input['id'];

            // 1. Update header + payment
            $this->purchaseRepository->updateById($id, [
                'supplier_id'  => $input['supplier_id'] ?? null,
                'warehouse_id' => $input['warehouse_id'] ?? null,
                'status'       => $input['status'] ?? 'draft',
                'payment'      => $input['payment'] ?? '',
                'description'  => $input['description'] ?? '',
                'paid_amount'  => $input['paid_amount'] ?? 0
            ]);

            // 2. Lấy item cũ để rollback kho
            $oldItems = $this->purchaseItemRepository->getByPurchaseId($id);

            $rollbackLogs = [];

            foreach ($oldItems as $item) {

                $rollbackLogs[] = [
                    'product_id'     => $item['product_id'],
                    'warehouse_id'   => $input['warehouse_id'] ?? null,
                    'type'           => 'out',
                    'quantity'       => $item['quantity'],
                    'reference_type' => 'purchase_update_rollback',
                    'reference_id'   => $id,
                    'note'           => 'rollback old items'
                ];
            }

            if (!empty($rollbackLogs)) {
                $this->inventoryTransactionRepository->createBatch($rollbackLogs);
            }

            // 3. Xóa item cũ
            $this->purchaseItemRepository->deleteByPurchaseId($id);

            // 4. Build item mới
            $items = [];
            $logs  = [];
            $total = 0;

            $products = $input['items']
                ?? $input['products']
                ?? [];

            foreach ($products as $p) {

                $productId = (int) ($p['product_id'] ?? $p['id'] ?? 0);
                $qty       = (float) ($p['quantity'] ?? 0);
                $price     = (float) ($p['price'] ?? 0);

                if ($productId <= 0 || $qty <= 0) {
                    continue;
                }

                $lineTotal = $qty * $price;
                $total += $lineTotal;

                $items[] = [
                    'purchase_id' => $id,
                    'product_id'  => $productId,
                    'quantity'    => $qty,
                    'unit_price'  => $price
                ];

                $logs[] = [
                    'product_id'     => $productId,
                    'warehouse_id'   => $input['warehouse_id'] ?? null,
                    'type'           => 'in',
                    'quantity'       => $qty,
                    'reference_type' => 'purchase_update',
                    'reference_id'   => $id,
                    'note'           => 're-import purchase'
                ];
            }

            if (empty($items)) {
                throw new Exception('Phiếu nhập phải có ít nhất 1 sản phẩm');
            }

            // 5. Insert item mới
            $this->purchaseItemRepository->createBatch($items);

            // 6. Ghi transaction nhập mới
            if (!empty($logs)) {
                $this->inventoryTransactionRepository->createBatch($logs);
            }

            // 7. Rebuild tồn kho cho cả sản phẩm cũ và mới
            $oldProductIds = array_column($oldItems, 'product_id');
            $newProductIds = array_column($items, 'product_id');

            $productIds = array_unique(
                array_merge($oldProductIds, $newProductIds)
            );

            $this->inventoryRepository->update($productIds);

            // 8. Tính lại công nợ
            $paid = (float) ($input['paid_amount'] ?? 0);
            $debt = max($total - $paid, 0);

            $this->purchaseRepository->updateById($id, [
                'total_amount' => $total,
                'paid_amount'  => $paid,
                'debt_amount'  => $debt
            ]);

            return $id;
        });
    }

    // =========================
    // DELETE PURCHASE
    // =========================
    public function delete(int $id): int
    {
        return Database::transaction(function () use ($id) {

            // 1. Get purchase (to ensure warehouse context)
            $purchase = $this->purchaseRepository->findById($id);

            if (!$purchase) {
                throw new Exception('Purchase not found');
            }

            // 2. Get items
            $items = $this->purchaseItemRepository->getByPurchaseId($id);

            // 3. Rollback stock (OUT)
            $rollbackLogs = [];

            foreach ($items as $item) {

                $rollbackLogs[] = [
                    'product_id'     => $item['product_id'],
                    'warehouse_id'   => $purchase['warehouse_id'],
                    'type'           => 'out',
                    'quantity'       => $item['quantity'],
                    'reference_type' => 'purchase_delete',
                    'reference_id'   => $id,
                    'note'           => 'delete purchase rollback'
                ];
            }

            if (!empty($rollbackLogs)) {
                $this->inventoryTransactionRepository->createBatch($rollbackLogs);
                
                $productIds = array_column($items, 'product_id');
                $this->inventoryRepository->update($productIds);
            }

            // 4. Delete items first
            $this->purchaseItemRepository->deleteByPurchaseId($id);

            // 5. Delete purchase header
            return $this->purchaseRepository->deleteById($id);
        });
    }
}