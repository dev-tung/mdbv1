<?php

class PurchaseEndpoint
{
    protected PurchaseModel $purchaseModel;
    protected PurchaseItemModel $purchaseItemModel;
    protected InventoryTransactionModel $inventoryTransactionModel;

    public function __construct()
    {
        $this->purchaseModel = new PurchaseModel();
        $this->purchaseItemModel = new PurchaseItemModel();
        $this->inventoryTransactionModel = new InventoryTransactionModel();
    }

    // =========================
    // LIST
    // =========================
    public function apiList()
    {
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = Config::get('pagination', 'default_per_page');

        $filters = request_filters(['keyword', 'supplier_id', 'status', 'payment']);

        $data = $this->purchaseModel->getList(
            $filters,
            $limit,
            ($page - 1) * $limit
        );

        $total = $this->purchaseModel->count($filters);

        return Response::json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'page'       => $page,
                'total'      => $total,
                'totalPages' => ceil($total / $limit),
                'perPage'    => $limit
            ]
        ]);
    }

    // =========================
    // SHOW (FIXED FOR FRONTEND)
    // =========================
    public function apiShow($id)
    {
        $id = (int)$id;

        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        $purchase = $this->purchaseModel->findById($id);

        if (!$purchase) {
            return Response::json([
                'success' => false,
                'message' => 'Không tìm thấy phiếu nhập'
            ]);
        }

        $items = $this->purchaseItemModel->getByPurchaseId($id);

        // normalize items -> frontend format
        $products = [];

        foreach ($items as $item) {
            $products[] = [
                'product_id' => $item['product_id'],
                'name'       => $item['product_name'] ?? '',
                'price'      => (float)$item['unit_price'],
                'quantity'   => (int)$item['quantity']
            ];
        }

        return Response::json([
            'success' => true,
            'data' => [
                'supplier_id'  => $purchase['supplier_id'],
                'warehouse_id' => $purchase['warehouse_id'],
                'status'       => $purchase['status'],
                'payment'      => $purchase['payment'],
                'description'  => $purchase['description'] ?? '',

                // optional display
                'supplier' => [
                    'name' => $purchase['supplier_name'] ?? ''
                ],

                'products' => $products
            ]
        ]);
    }

    // =========================
    // CREATE
    // =========================
    public function apiCreate()
    {
        $input = Request::all();

        $error = PurchaseValidator::validate($input);

        if ($error) {
            return Response::json([
                'success' => false,
                'message' => $error
            ]);
        }

        try {

            Database::beginTransaction();

            // tạo phiếu nhập
            $purchaseId = $this->purchaseModel->create([
                'supplier_id'  => (int)$input['supplier_id'],
                'warehouse_id' => (int)$input['warehouse_id'],
                'status'       => $input['status'] ?? 'draft',
                'total_cost'   => 0,
                'description'  => trim($input['description'] ?? ''),
                'payment'      => $input['payment'] ?? ''
            ]);

            $total = 0;

            $purchaseItems = [];
            $inventoryLogs = [];

            foreach ($input['products'] as $item) {

                $product_id = (int)($item['product_id'] ?? $item['id'] ?? 0);
                $qty        = (int)($item['quantity'] ?? 1);
                $price      = (float)($item['price'] ?? 0);

                $total += $qty * $price;

                $purchaseItems[] = [
                    'purchase_id' => $purchaseId,
                    'product_id'  => $product_id,
                    'quantity'    => $qty,
                    'unit_price'  => $price
                ];

                $inventoryLogs[] = [
                    'product_id'     => $product_id,
                    'warehouse_id'   => (int)$input['warehouse_id'],
                    'type'           => 'in',
                    'quantity'       => $qty,
                    'reference_type' => 'purchase',
                    'reference_id'   => $purchaseId,
                    'note'           => 'Nhập kho từ phiếu nhập'
                ];
            }

            // insert batch
            $this->purchaseItemModel->insertBatch(
                $purchaseItems
            );

            $this->inventoryTransactionModel->insertBatch(
                $inventoryLogs
            );

            // update total
            $this->purchaseModel->updateById($purchaseId, [
                'total_cost' => $total
            ]);

            Database::commit();

            return Response::json([
                'success' => true,
                'message' => 'Tạo phiếu nhập thành công',
                'id'      => $purchaseId
            ]);

        } catch (Throwable $e) {

            Database::rollback();

            return Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // =========================
    // UPDATE
    // =========================
    public function apiUpdate()
    {
        $input = Request::all();

        $id = (int)($input['id'] ?? 0);

        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        $error = PurchaseValidator::validate($input);

        if ($error) {
            return Response::json([
                'success' => false,
                'message' => $error
            ]);
        }

        $oldPurchase = $this->purchaseModel->findById($id);

        if (!$oldPurchase) {
            return Response::json([
                'success' => false,
                'message' => 'Không tìm thấy phiếu nhập'
            ]);
        }

        try {

            Database::beginTransaction();

            // lấy dữ liệu cũ để rollback kho
            $oldItems = $this->purchaseItemModel
                ->getByPurchaseId($id);

            // update header
            $this->purchaseModel->updateById($id, [
                'supplier_id'  => (int)$input['supplier_id'],
                'warehouse_id' => (int)$input['warehouse_id'],
                'status'       => $input['status'] ?? '',
                'payment'      => $input['payment'] ?? '',
                'description'  => trim($input['description'] ?? '')
            ]);

            // rollback tồn kho cũ
            $rollbackLogs = [];

            foreach ($oldItems as $item) {

                $rollbackLogs[] = [
                    'product_id'     => $item['product_id'],
                    'warehouse_id'   => $oldPurchase['warehouse_id'],
                    'type'           => 'out',
                    'quantity'       => $item['quantity'],
                    'reference_type' => 'purchase_update_old',
                    'reference_id'   => $id,
                    'note'           => 'Rollback phiếu nhập cũ'
                ];
            }

            if (!empty($rollbackLogs)) {
                $this->inventoryTransactionModel
                    ->insertBatch($rollbackLogs);
            }

            // xóa item cũ
            $this->purchaseItemModel
                ->deleteByPurchaseId($id);

            $purchaseItems = [];
            $inventoryLogs = [];
            $total = 0;

            // tạo item mới
            foreach ($input['products'] as $item) {

                $product_id = (int)($item['product_id'] ?? $item['id'] ?? 0);
                $qty        = (int)($item['quantity'] ?? 1);
                $price      = (float)($item['price'] ?? 0);

                $total += $qty * $price;

                $purchaseItems[] = [
                    'purchase_id' => $id,
                    'product_id'  => $product_id,
                    'quantity'    => $qty,
                    'unit_price'  => $price
                ];

                $inventoryLogs[] = [
                    'product_id'     => $product_id,
                    'warehouse_id'   => (int)$input['warehouse_id'],
                    'type'           => 'in',
                    'quantity'       => $qty,
                    'reference_type' => 'purchase_update_new',
                    'reference_id'   => $id,
                    'note'           => 'Cập nhật phiếu nhập'
                ];
            }

            // batch insert
            $this->purchaseItemModel
                ->insertBatch($purchaseItems);

            $this->inventoryTransactionModel
                ->insertBatch($inventoryLogs);

            // update total
            $this->purchaseModel->updateById($id, [
                'total_cost' => $total
            ]);

            Database::commit();

            return Response::json([
                'success' => true,
                'message' => 'Cập nhật thành công',
                'id'      => $id
            ]);

        } catch (Throwable $e) {

            Database::rollback();

            return Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // =========================
    // DELETE
    // =========================
    public function apiDelete()
    {
        $id = (int) Request::input('id', 0);

        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        $purchase = $this->purchaseModel->findById($id);

        if (!$purchase) {
            return Response::json([
                'success' => false,
                'message' => 'Không tìm thấy phiếu nhập'
            ]);
        }

        try {

            Database::beginTransaction();

            // lấy items để rollback kho
            $items = $this->purchaseItemModel
                ->getByPurchaseId($id);

            // chuẩn bị lịch sử xuất kho
            $inventoryLogs = [];

            foreach ($items as $item) {

                $inventoryLogs[] = [
                    'product_id'     => $item['product_id'],
                    'warehouse_id'   => $purchase['warehouse_id'],
                    'type'           => 'out',
                    'quantity'       => $item['quantity'],
                    'reference_type' => 'purchase_delete',
                    'reference_id'   => $id,
                    'note'           => 'Hủy phiếu nhập - hoàn kho'
                ];
            }

            // ghi lịch sử kho
            if (!empty($inventoryLogs)) {

                $this->inventoryTransactionModel
                    ->insertBatch($inventoryLogs);
            }

            // xóa chi tiết
            $this->purchaseItemModel
                ->deleteByPurchaseId($id);

            // xóa phiếu nhập
            $deleted = $this->purchaseModel
                ->deleteById($id);

            Database::commit();

            return Response::json([
                'success' => $deleted > 0,
                'message' => $deleted > 0
                    ? 'Xóa thành công'
                    : 'Không tìm thấy phiếu nhập'
            ]);

        } catch (Throwable $e) {

            Database::rollback();

            return Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // =========================
    // UPDATE STATUS
    // =========================
    public function apiStatus()
    {
        $id = (int)($_POST['id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        // validate ID
        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        // update status
        $updated = $this->purchaseModel->updateById($id, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return Response::json([
            'success' => $updated > 0,
            'message' => $updated > 0 ? 'Cập nhật trạng thái thành công' : 'Không tìm thấy phiếu nhập'
        ]);
    }

    // =========================
    // UPDATE PAYMENT STATUS
    // =========================
    public function apiPayment()
    {
        $id = (int)($_POST['id'] ?? 0);
        $paymentStatus = trim($_POST['payment'] ?? '');

        // validate ID
        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        // validate payment status from config
        $allowed = array_keys(config('shop.option.payment') ?? []);

        if (!in_array($paymentStatus, $allowed)) {
            return Response::json([
                'success' => false,
                'message' => 'Trạng thái thanh toán không hợp lệ'
            ]);
        }

        // update
        $updated = $this->purchaseModel->updateById($id, [
            'payment' => $paymentStatus,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return Response::json([
            'success' => $updated > 0,
            'message' => $updated > 0 
                ? 'Cập nhật thanh toán thành công'
                : 'Không tìm thấy phiếu nhập'
        ]);
    }
}