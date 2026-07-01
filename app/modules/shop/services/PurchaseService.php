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

            $total      = 0;
            $logs       = [];
            $productIds = [];

            foreach ($input['items'] ?? $input['products'] ?? [] as $p) {

                $productId = (int)($p['product_id'] ?? $p['id'] ?? 0);
                $qty       = (float)($p['quantity'] ?? 1);
                $price     = (float)($p['price'] ?? 0);

                if ($productId <= 0 || $qty <= 0) {
                    continue;
                }

                $lineTotal = $qty * $price;
                $total += $lineTotal;

                // Tạo purchase item (lot)
                $purchaseItemId = $this->purchaseItemRepository->create([
                    'purchase_id' => $purchaseId,
                    'product_id'  => $productId,
                    'quantity'    => $qty,
                    'unit_price'  => $price
                ]);

                // Ghi nhận nhập kho
                $logs[] = [
                    'purchase_item_id' => $purchaseItemId,
                    'product_id'       => $productId,
                    'type'             => 'in',
                    'quantity'         => $qty,
                    'reference_type'   => 'purchase',
                    'reference_id'     => $purchaseId,
                    'note'             => 'Import purchase'
                ];

                $productIds[] = $productId;
            }

            if (!empty($logs)) {
                $this->inventoryTransactionRepository->createBatch($logs);

                $this->inventoryRepository->update(
                    array_values(array_unique($productIds))
                );
            }

            $paid = (float)($input['paid_amount'] ?? 0);
            $debt = max($total - $paid, 0);

            $this->purchaseRepository->updateById($purchaseId, [
                'total_amount' => $total,
                'debt_amount'  => $debt
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

            $id = (int)$input['id'];

            // 1. Update header
            $this->purchaseRepository->updateById($id, [
                'supplier_id'  => $input['supplier_id'] ?? null,
                'warehouse_id' => $input['warehouse_id'] ?? null,
                'status'       => $input['status'] ?? 'draft',
                'payment'      => $input['payment'] ?? '',
                'description'  => $input['description'] ?? '',
                'paid_amount'  => $input['paid_amount'] ?? 0
            ]);

            // 2. Lấy item cũ
            $oldItems = $this->purchaseItemRepository->getByPurchaseId($id);

            $productIds = [];

            // 3. Rollback tồn kho theo từng lot
            foreach ($oldItems as $item) {

                $this->inventoryTransactionRepository->create([
                    'purchase_item_id' => $item['id'],
                    'product_id'       => $item['product_id'],
                    'type'             => 'out',
                    'quantity'         => $item['quantity'],
                    'reference_type'   => 'purchase_update',
                    'reference_id'     => $id,
                    'note'             => 'Rollback purchase'
                ]);

                $productIds[] = $item['product_id'];
            }

            // 4. Xóa purchase item cũ
            $this->purchaseItemRepository->deleteByPurchaseId($id);

            // 5. Insert purchase item mới
            $total = 0;

            foreach (($input['items'] ?? $input['products'] ?? []) as $p) {

                $productId = (int)($p['product_id'] ?? $p['id'] ?? 0);
                $qty       = (float)($p['quantity'] ?? 0);
                $price     = (float)($p['price'] ?? 0);

                if ($productId <= 0 || $qty <= 0) {
                    continue;
                }

                $total += $qty * $price;

                $purchaseItemId = $this->purchaseItemRepository->create([
                    'purchase_id' => $id,
                    'product_id'  => $productId,
                    'quantity'    => $qty,
                    'unit_price'  => $price
                ]);

                $this->inventoryTransactionRepository->create([
                    'purchase_item_id' => $purchaseItemId,
                    'product_id'       => $productId,
                    'type'             => 'in',
                    'quantity'         => $qty,
                    'reference_type'   => 'purchase_update',
                    'reference_id'     => $id,
                    'note'             => 'Import purchase'
                ]);

                $productIds[] = $productId;
            }

            if (empty($productIds)) {
                throw new Exception('Phiếu nhập phải có ít nhất 1 sản phẩm');
            }

            // 6. Rebuild tồn kho
            $this->inventoryRepository->update(
                array_values(array_unique($productIds))
            );

            // 7. Update tiền
            $paid = (float)($input['paid_amount'] ?? 0);
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

            // 1. Get purchase
            $purchase = $this->purchaseRepository->findById($id);

            if (!$purchase) {
                throw new Exception('Purchase not found');
            }

            // 2. Get purchase items
            $items = $this->purchaseItemRepository->getByPurchaseId($id);

            $logs = [];
            $productIds = [];

            foreach ($items as $item) {

                $logs[] = [
                    'purchase_item_id' => $item['id'],
                    'product_id'       => $item['product_id'],
                    'type'             => 'out',
                    'quantity'         => $item['quantity'],
                    'reference_type'   => 'purchase_delete',
                    'reference_id'     => $id,
                    'note'             => 'Delete purchase rollback'
                ];

                $productIds[] = $item['product_id'];
            }

            // 3. Rollback stock
            if (!empty($logs)) {

                $this->inventoryTransactionRepository->createBatch($logs);

                $this->inventoryRepository->update(
                    array_values(array_unique($productIds))
                );
            }

            // 4. Delete purchase items
            $this->purchaseItemRepository->deleteByPurchaseId($id);

            // 5. Delete purchase
            return $this->purchaseRepository->deleteById($id);
        });
    }

    // =========================
    // UPDATE PAYMENT
    // =========================
    public function updatePayment(int $id, array $input): int
    {
        $purchase = $this->purchaseRepository->findById($id);

        if (!$purchase) {
            throw new Exception('Đơn hàng không tồn tại');
        }

        $payment = $input['payment'] ?? null;

        $dataUpdate = [
            'payment' => $payment,
        ];

        // Nếu đã thanh toán hết
        if ($payment === 'paid') {
            $dataUpdate['paid_amount'] = (float) $purchase['total_amount'];
            $dataUpdate['debt_amount'] = 0;
        }

        return $this->purchaseRepository->updateById($id, $dataUpdate);
    }
}