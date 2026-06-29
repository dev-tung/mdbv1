<?php

class OrderEndpoint
{
    protected OrderRepository $orderRepository;
    protected OrderItemRepository $orderItemRepository;
    protected InventoryTransactionRepository $inventoryTransactionRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
        $this->orderItemRepository = new OrderItemRepository();
        $this->inventoryTransactionRepository = new InventoryTransactionRepository();
    }

    // =========================
    // LIST
    // =========================
    public function apiList()
    {
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = Config::get('pagination', 'default_per_page');

        $filters = request_filters(['keyword', 'customer_id', 'status', 'payment']);

        $data = $this->orderRepository->getList(
            $filters,
            $limit,
            ($page - 1) * $limit
        );

        $total = $this->orderRepository->count($filters);

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
    // SHOW
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

        $order = $this->orderRepository->findById($id);

        if (!$order) {
            return Response::json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ]);
        }

        $items = $this->orderItemRepository->getByOrderId($id);

        $products = [];

        foreach ($items as $item) {
            $products[] = [
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'purchase_item_id' => $item['purchase_item_id'],
                'price'      => (float)$item['price'],
                'quantity'   => (int)$item['quantity'],
                'discount'   => (float)$item['discount']
            ];
        }

        return Response::json([
            'success' => true,
            'data' => [
                'customer_id' => $order['customer_id'],
                'status'      => $order['status'],
                'payment'     => $order['payment'],
                'description' => $order['description'] ?? '',

                'customer' => [
                    'name' => $order['customer_name'] ?? ''
                ],

                'products' => $products
            ]
        ]);
    }

    public function apiCreate()
    {
        // LẤY DỮ LIỆU REQUEST
        $input = json_decode(file_get_contents("php://input"), true);

        $customer_id = (int)($input['customer_id'] ?? 0);
        $status      = $input['status'] ?? 'pending';
        $payment     = $input['payment'] ?? 'unpaid';
        $items       = $input['products'] ?? [];
        $description = $input['description'] ?? '';

        // KIỂM TRA DỮ LIỆU
        if ($customer_id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'Khách hàng không hợp lệ'
            ]);
        }

        if (empty($items)) {
            return Response::json([
                'success' => false,
                'message' => 'Chưa có sản phẩm'
            ]);
        }

        // TẠO ĐƠN HÀNG
        $orderId = $this->orderRepository->create([
            'customer_id' => $customer_id,
            'status'      => $status,
            'payment'     => $payment,
            'description' => $description
        ]);

        $total = 0;

        // TẠO CHI TIẾT ĐƠN HÀNG
        foreach ($items as $item) {

            $product_id      = (int)($item['product_id'] ?? $item['id'] ?? 0);
            $qty             = (int)($item['quantity'] ?? 1);
            $price           = (float)($item['price'] ?? 0);
            $discount        = (float)($item['discount'] ?? 0);
            $purchase_item_id = (int)($item['purchase_item_id'] ?? 0);

            if ($product_id <= 0 || $qty <= 0) {
                continue;
            }

            $lineTotal = ($qty * $price) - $discount;
            $total += $lineTotal;

            $this->orderItemRepository->create([
                'order_id'         => $orderId,
                'product_id'       => $product_id,
                'purchase_item_id' => $purchase_item_id,
                'quantity'         => $qty,
                'price'            => $price,
                'discount'         => $discount
            ]);

            // Tạo lịch sử xuất kho
            $this->inventoryTransactionRepository->create([
                'product_id'     => $product_id,
                'type'           => 'out',
                'quantity'       => $qty,
                'reference_type' => 'order',
                'reference_id'   => $orderId,
                'note'           => 'Xuất kho bán hàng'
            ]);
        }

        // CẬP NHẬT TỔNG TIỀN
        $this->orderRepository->updateById($orderId, [
            'total_amount' => $total
        ]);

        // TRẢ KẾT QUẢ
        return Response::json([
            'success' => true,
            'message' => 'Tạo đơn hàng thành công',
            'id'      => $orderId
        ]);
    }

    // =========================
    // UPDATE
    // =========================
    public function apiUpdate()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        $orderId = (int)($input['id'] ?? 0);

        $customer_id = (int)($input['customer_id'] ?? 0);
        $status      = $input['status'] ?? 'pending';
        $payment     = $input['payment'] ?? 'unpaid';
        $items       = $input['products'] ?? [];
        $description = $input['description'] ?? '';

        // =========================
        // VALIDATE
        // =========================
        if ($orderId <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        if ($customer_id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'Khách hàng không hợp lệ'
            ]);
        }

        if (empty($items)) {
            return Response::json([
                'success' => false,
                'message' => 'Chưa có sản phẩm'
            ]);
        }

        // =========================
        // UPDATE ORDER HEADER
        // =========================
        $this->orderRepository->updateById($orderId, [
            'customer_id' => $customer_id,
            'status'      => $status,
            'payment'     => $payment,
            'description' => $description,
            'updated_at'  => date('Y-m-d H:i:s')
        ]);

        // =========================
        // DELETE OLD DATA
        // =========================

        // Xóa chi tiết đơn hàng cũ
        $this->orderItemRepository->deleteByOrderId($orderId);

        // Xóa lịch sử xuất kho cũ
        $this->inventoryTransactionRepository->deleteByReference(
            'order',
            $orderId
        );

        // =========================
        // INSERT NEW ITEMS
        // =========================
        $total = 0;

        foreach ($items as $item) {

            $product_id       = (int)($item['product_id'] ?? $item['id'] ?? 0);
            $qty              = (int)($item['quantity'] ?? 1);
            $price            = (float)($item['price'] ?? 0);
            $discount         = (float)($item['discount'] ?? 0);
            $purchase_item_id = (int)($item['purchase_item_id'] ?? 0);
            $warehouse_id     = (int)($item['warehouse_id'] ?? 0);

            if ($product_id <= 0 || $qty <= 0) {
                continue;
            }

            $lineTotal = ($qty * $price) - $discount;
            $total += $lineTotal;

            // Tạo lại order item
            $this->orderItemRepository->create([
                'order_id'         => $orderId,
                'product_id'       => $product_id,
                'purchase_item_id' => $purchase_item_id,
                'quantity'         => $qty,
                'price'            => $price,
                'discount'         => $discount
            ]);

            // Tạo lại lịch sử xuất kho
            $this->inventoryTransactionRepository->create([
                'product_id'     => $product_id,
                'warehouse_id'   => $warehouse_id,
                'type'           => 'out',
                'quantity'       => $qty,
                'reference_type' => 'order',
                'reference_id'   => $orderId,
                'note'           => 'Xuất kho bán hàng'
            ]);
        }

        // =========================
        // UPDATE TOTAL
        // =========================
        $this->orderRepository->updateById($orderId, [
            'total_amount' => $total
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Cập nhật đơn hàng thành công',
            'id'      => $orderId
        ]);
    }

    // =========================
    // UPDATE STATUS
    // =========================
    public function apiStatus()
    {
        $id = (int)($_POST['id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        $updated = $this->orderRepository->updateById($id, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return Response::json([
            'success' => $updated > 0,
            'message' => $updated > 0 ? 'Cập nhật trạng thái thành công' : 'Không tìm thấy đơn hàng'
        ]);
    }

    // =========================
    // UPDATE PAYMENT
    // =========================
    public function apiPayment()
    {
        $id = (int)($_POST['id'] ?? 0);
        $payment = trim($_POST['payment'] ?? '');

        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        $allowed = array_keys(config('shop.option.payment') ?? []);

        if (!in_array($payment, $allowed)) {
            return Response::json([
                'success' => false,
                'message' => 'Trạng thái thanh toán không hợp lệ'
            ]);
        }

        $updated = $this->orderRepository->updateById($id, [
            'payment' => $payment,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return Response::json([
            'success' => $updated > 0,
            'message' => $updated > 0 ? 'Cập nhật thanh toán thành công' : 'Không tìm thấy đơn hàng'
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

        // Xóa lịch sử xuất kho
        $this->inventoryTransactionRepository->deleteByReference(
            'order',
            $id
        );

        // Xóa chi tiết đơn hàng
        $this->orderItemRepository->deleteByOrderId($id);

        // Xóa đơn hàng
        $deleted = $this->orderRepository->deleteById($id);

        return Response::json([
            'success' => $deleted > 0,
            'message' => $deleted > 0
                ? 'Xóa thành công'
                : 'Không tìm thấy đơn hàng'
        ]);
    }
}