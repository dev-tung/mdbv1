<?php

class PurchaseService
{
    private InventoryRepository $inventoryRepository;
    private PurchaseRepository $purchaseRepository;

    public function __construct()
    {
        $this->inventoryRepository = new InventoryRepository();
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

            return $id;
        });
    }

    // =========================
    // DELETE PURCHASE
    // =========================
    public function delete(int $id): int
    {
        return Database::transaction(function () use ($id) {
            // Delete purchase
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