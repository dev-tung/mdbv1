<?php

class OrderService
{
    private OrderRepository $orderRepository;
    private OrderItemRepository $orderItemRepository;
    private InventoryTransactionRepository $inventoryTransactionRepository;
    private InventoryRepository $inventoryRepository;

    public function __construct()
    {
        $this->orderRepository             = new OrderRepository();
        $this->orderItemRepository         = new OrderItemRepository();
        $this->inventoryTransactionRepository = new InventoryTransactionRepository();
        $this->inventoryRepository            = new InventoryRepository();
    }

    // =========================
    // LIST ORDER
    // =========================
    public function getList(array $input): array
    {
        $page  = $input['page'] ?? 1;
        $limit = Config::get('pagination', 'default_per_page');
        $offset = ($page - 1) * $limit;

        $filters = [
            'keyword'     => $input['keyword'] ?? null,
            'customer_id' => $input['customer_id'] ?? null,
            'status'      => $input['status'] ?? null,
            'payment'     => $input['payment'] ?? null,
        ];

        $data  = $this->orderRepository->getList($filters, $limit, $offset);
        $total = $this->orderRepository->count($filters);

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
    // SHOW ORDER
    // =========================
    public function show(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $order = $this->orderRepository->findById($id);

        if (!$order) {
            return null;
        }

        $items = $this->orderItemRepository->getByOrderId($id);

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
            'id'           => (int) $order['id'],
            'customer_id'  => (int) $order['customer_id'],
            'status'       => $order['status'],
            'payment'      => $order['payment'],
            'description'  => $order['description'] ?? '',
            'customer' => [
                'name' => $order['customer_name'] ?? ''
            ],
            'total_amount' => (float) ($order['total_amount'] ?? 0),
            'paid_amount' => (float) ($order['paid_amount'] ?? 0),
            'debt_amount' => (float) ($order['debt_amount'] ?? 0),
            'products' => $products
        ];
    }

    // =========================
    // CREATE ORDER
    // =========================
    public function create(array $input): int
    {
        return Database::transaction(function () use ($input) {

            $orderId = $this->orderRepository->create([
                'customer_id'  => $input['customer_id'] ?? null,
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

                $purchaseItemId = $p['purchase_item_id'] ?? null;

                if ($productId <= 0 || $qty <= 0) {
                    continue;
                }

                $lineTotal = $qty * $price;
                $total += $lineTotal;

                // =========================
                // ORDER ITEMS (ADD FIELD)
                // =========================
                $items[] = [
                    'order_id'          => $orderId,
                    'product_id'        => $productId,
                    'quantity'          => $qty,
                    'unit_price'        => $price,
                    'purchase_item_id'  => $purchaseItemId
                ];

                // =========================
                // INVENTORY LOG (ADD FIELD)
                // =========================
                $logs[] = [
                    'product_id'        => $productId,
                    'type'              => 'out',
                    'quantity'          => $qty,
                    'reference_type'    => 'order',
                    'reference_id'      => $orderId,
                    'purchase_item_id'  => $purchaseItemId,
                    'note'              => 'import order'
                ];
            }

            if (!empty($items)) {
                $this->orderItemRepository->createBatch($items);
            }

            if (!empty($logs)) {
                $this->inventoryTransactionRepository->createBatch($logs);

                $productIds = array_values(array_unique(array_column($items, 'product_id')));
                $this->inventoryRepository->update($productIds);
            }

            $debt = max($total - ($input['paid_amount'] ?? 0), 0);

            $this->orderRepository->updateById($orderId, [
                'total_amount'=> $total,
                'debt_amount' => $debt
            ]);

            return $orderId;
        });
    }

    // =========================
    // UPDATE ORDER
    // =========================
    public function update(array $input): int
    {
        return Database::transaction(function () use ($input) {

            $id = $input['id'];

            // 1. Update header + payment
            $this->orderRepository->updateById($id, [
                'customer_id'  => $input['customer_id'] ?? null,
                'status'       => $input['status'] ?? 'draft',
                'payment'      => $input['payment'] ?? '',
                'description'  => $input['description'] ?? '',
                'paid_amount'  => $input['paid_amount'] ?? 0
            ]);

            // 2. Lấy item cũ để rollback kho
            $oldItems = $this->orderItemRepository->getByOrderId($id);

            $rollbackLogs = [];

            foreach ($oldItems as $item) {

                $rollbackLogs[] = [
                    'product_id'     => $item['product_id'],
                    'type'           => 'out',
                    'quantity'       => $item['quantity'],
                    'reference_type' => 'order_update_rollback',
                    'reference_id'   => $id,
                    'purchase_item_id' => $item['purchase_item_id'] ?? null,
                    'note'           => 'rollback old items'
                ];
            }

            if (!empty($rollbackLogs)) {
                $this->inventoryTransactionRepository->createBatch($rollbackLogs);
            }

            // 3. Xóa item cũ
            $this->orderItemRepository->deleteByOrderId($id);

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

                $purchaseItemId = $p['purchase_item_id'] ?? null;

                if ($productId <= 0 || $qty <= 0) {
                    continue;
                }

                $lineTotal = $qty * $price;
                $total += $lineTotal;

                // =========================
                // ORDER ITEMS
                // =========================
                $items[] = [
                    'order_id'          => $id,
                    'product_id'        => $productId,
                    'quantity'          => $qty,
                    'unit_price'        => $price,
                    'purchase_item_id'  => $purchaseItemId
                ];

                // =========================
                // INVENTORY LOG
                // =========================
                $logs[] = [
                    'product_id'        => $productId,
                    'type'              => 'in',
                    'quantity'          => $qty,
                    'reference_type'    => 'order_update',
                    'reference_id'      => $id,
                    'purchase_item_id'  => $purchaseItemId,
                    'note'              => 're-import order'
                ];
            }

            if (empty($items)) {
                throw new Exception('Đơn hàng phải có ít nhất 1 sản phẩm');
            }

            // 5. Insert item mới
            $this->orderItemRepository->createBatch($items);

            // 6. Ghi transaction nhập mới
            if (!empty($logs)) {
                $this->inventoryTransactionRepository->createBatch($logs);
            }

            // 7. Rebuild tồn kho
            $oldProductIds = array_values(array_column($oldItems, 'product_id'));
            $newProductIds = array_values(array_column($items, 'product_id'));

            $productIds = array_values(array_unique(array_merge(
                $oldProductIds,
                $newProductIds
            )));

            $this->inventoryRepository->update($productIds);

            // 8. Tính lại công nợ
            $paid = (float) ($input['paid_amount'] ?? 0);
            $debt = max($total - $paid, 0);

            $this->orderRepository->updateById($id, [
                'total_amount' => $total,
                'paid_amount'  => $paid,
                'debt_amount'  => $debt
            ]);

            return $id;
        });
    }

    // =========================
    // DELETE ORDER
    // =========================
    public function delete(int $id): int
    {
        return Database::transaction(function () use ($id) {

            // 1. Get order
            $order = $this->orderRepository->findById($id);

            if (!$order) {
                throw new Exception('Order not found');
            }

            // 2. Get items
            $items = $this->orderItemRepository->getByOrderId($id);

            // 3. Rollback stock
            $rollbackLogs = [];

            foreach ($items as $item) {

                $rollbackLogs[] = [
                    'product_id'        => $item['product_id'],
                    'type'              => 'out',
                    'quantity'          => $item['quantity'],
                    'reference_type'    => 'order_delete',
                    'reference_id'      => $id,
                    'purchase_item_id'  => $item['purchase_item_id'] ?? null,
                    'note'              => 'delete order rollback'
                ];
            }

            if (!empty($rollbackLogs)) {
                $this->inventoryTransactionRepository->createBatch($rollbackLogs);

                $productIds = array_values(array_unique(array_column($items, 'product_id')));
                $this->inventoryRepository->update($productIds);
            }

            // 4. Delete items first
            $this->orderItemRepository->deleteByOrderId($id);

            // 5. Delete order header
            return $this->orderRepository->deleteById($id);
        });
    }

    // =========================
    // UPDATE PAYMENT
    // =========================
    public function updatePayment(int $id, array $input): int
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            throw new Exception('Đơn hàng không tồn tại');
        }

        $payment = $input['payment'] ?? null;

        $dataUpdate = [
            'payment' => $payment,
        ];

        // Nếu đã thanh toán hết
        if ($payment === 'paid') {
            $dataUpdate['paid_amount'] = (float) $order['total_amount'];
            $dataUpdate['debt_amount'] = 0;
        }

        return $this->orderRepository->updateById($id, $dataUpdate);
    }
}