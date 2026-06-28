<?php

class PurchaseEndpoint
{
    protected PurchaseModel $purchaseModel;
    protected PurchaseItemModel $purchaseItemModel;

    public function __construct()
    {
        $this->purchaseModel = new PurchaseModel();
        $this->purchaseItemModel = new PurchaseItemModel();
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
        $input = json_decode(file_get_contents("php://input"), true);

        $supplier_id  = (int)($input['supplier_id'] ?? 0);
        $warehouse_id = (int)($input['warehouse_id'] ?? 0);
        $status       = $input['status'] ?? 'draft';
        $items        = $input['products'] ?? [];
        $description  = $input['description'];
        $payment      = $input['payment'] ?? '';

        if ($supplier_id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'Nhà cung cấp không hợp lệ'
            ]);
        }

        if (empty($items)) {
            return Response::json([
                'success' => false,
                'message' => 'Chưa có sản phẩm'
            ]);
        }


        $purchaseId = $this->purchaseModel->create([
            'supplier_id'  => $supplier_id,
            'warehouse_id' => $warehouse_id,
            'status'       => $status,
            'total_cost'   => 0,
            'description'  => $description,
            'payment'      => $payment
        ]);

        $total = 0;

        foreach ($items as $item) {

            $product_id = (int)($item['product_id'] ?? $item['id'] ?? 0);
            $qty        = (int)($item['quantity'] ?? 1);
            $price      = (float)($item['price'] ?? 0);

            if ($product_id <= 0 || $qty <= 0) {
                continue;
            }

            $total += $qty * $price;

            $this->purchaseItemModel->create([
                'purchase_id' => $purchaseId,
                'product_id'  => $product_id,
                'quantity'    => $qty,
                'unit_price'  => $price
            ]);
        }

        $this->purchaseModel->updateById($purchaseId, [
            'total_cost' => $total
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Tạo phiếu nhập thành công',
            'id'      => $purchaseId
        ]);
    }

    // =========================
    // UPDATE (FULL EDIT SUPPORT)
    // =========================
    public function apiUpdate()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        $id = (int)($input['id'] ?? 0);

        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        $supplier_id  = (int)($input['supplier_id'] ?? 0);
        $warehouse_id = (int)($input['warehouse_id'] ?? 0);
        $status       = $input['status'] ?? '';
        $items        = $input['products'] ?? [];
        $description  = $input['description'];
        $payment      = $input['payment'] ?? '';

        // update header
        $this->purchaseModel->updateById($id, [
            'supplier_id'  => $supplier_id,
            'warehouse_id' => $warehouse_id,
            'status'       => $status,
            'payment'      => $payment,
            'description'  => $description
        ]);

        // delete old items
        $this->purchaseItemModel->deleteByPurchaseId($id);

        $total = 0;

        // reinsert items
        foreach ($items as $item) {

            $product_id = (int)($item['product_id'] ?? 0);
            $qty        = (int)($item['quantity'] ?? 1);
            $price      = (float)($item['price'] ?? 0);

            if ($product_id <= 0 || $qty <= 0) {
                continue;
            }

            $total += $qty * $price;

            $this->purchaseItemModel->create([
                'purchase_id' => $id,
                'product_id'  => $product_id,
                'quantity'    => $qty,
                'unit_price'  => $price
            ]);
        }

        // update total
        $this->purchaseModel->updateById($id, [
            'total_cost' => $total
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Cập nhật thành công'
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function apiDelete()
    {
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        $this->purchaseItemModel->deleteByPurchaseId($id);
        $deleted = $this->purchaseModel->deleteById($id);

        return Response::json([
            'success' => $deleted > 0,
            'message' => $deleted > 0 ? 'Xóa thành công' : 'Không tìm thấy phiếu nhập'
        ]);
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