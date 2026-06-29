<?php

class PurchaseService
{
    private PurchaseRepository $purchaseRepository;
    private PurchaseItemRepository $itemRepository;
    private InventoryTransactionRepository $inventoryRepository;

    public function __construct()
    {
        $this->purchaseRepository  = new PurchaseRepository();
        $this->itemRepository      = new PurchaseItemRepository();
        $this->inventoryRepository = new InventoryTransactionRepository();
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
    // CREATE PURCHASE
    // =========================
    public function create(array $input): int
    {
        return Database::transaction(function () use ($input) {

            // 1. Create purchase
            $purchaseId = $this->purchaseRepository->create([
                'supplier_id'  => $input['supplier_id'] ?? null,
                'warehouse_id' => $input['warehouse_id'] ?? null,
                'status'       => $input['status'] ?? 'draft',
                'payment'      => $input['payment'] ?? '',
                'description'  => $input['description'] ?? '',
                'total_cost'   => 0
            ]);

            $total = 0;
            $items = [];
            $logs  = [];

            // 2. Build items + inventory logs
            foreach ($input['products'] ?? [] as $p) {

                $productId = $p['product_id'] ?? $p['id'] ?? 0;
                $qty       = $p['quantity'] ?? 1;
                $price     = $p['price'] ?? 0;

                if ($productId <= 0 || $qty <= 0) {
                    continue;
                }

                $total += $qty * $price;

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
                $this->itemRepository->insertBatch($items);
            }

            if (!empty($logs)) {
                $this->inventoryRepository->insertBatch($logs);
            }

            $this->purchaseRepository->updateById($purchaseId, [
                'total_cost' => $total
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

            // 1. Update purchase header
            $this->purchaseRepository->updateById($id, [
                'supplier_id'  => $input['supplier_id'] ?? null,
                'warehouse_id' => $input['warehouse_id'] ?? null,
                'status'       => $input['status'] ?? '',
                'payment'      => $input['payment'] ?? '',
                'description'  => $input['description'] ?? ''
            ]);

            // 2. Rollback old stock (based on current DB items)
            $rollbackLogs = [];

            foreach ($this->itemRepository->getByPurchaseId($id) as $item) {

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
                $this->inventoryRepository->insertBatch($rollbackLogs);
            }

            // 3. Delete old items
            $this->itemRepository->deleteByPurchaseId($id);

            // 4. Rebuild items
            $items = [];
            $logs  = [];
            $total = 0;

            foreach ($input['products'] ?? [] as $p) {

                $productId = $p['product_id'] ?? $p['id'] ?? 0;
                $qty       = $p['quantity'] ?? 1;
                $price     = $p['price'] ?? 0;

                if ($productId <= 0 || $qty <= 0) {
                    continue;
                }

                $total += $qty * $price;

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

            if (!empty($items)) {
                $this->itemRepository->insertBatch($items);
            }

            if (!empty($logs)) {
                $this->inventoryRepository->insertBatch($logs);
            }

            // 5. Update total
            $this->purchaseRepository->updateById($id, [
                'total_cost' => $total
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
            $items = $this->itemRepository->getByPurchaseId($id);

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
                $this->inventoryRepository->insertBatch($rollbackLogs);
            }

            // 4. Delete items first
            $this->itemRepository->deleteByPurchaseId($id);

            // 5. Delete purchase header
            return $this->purchaseRepository->deleteById($id);
        });
    }
}