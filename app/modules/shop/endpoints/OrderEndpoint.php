<?php

class OrderEndpoint
{
    private OrderRepository $orderRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    // =========================
    // LIST
    // =========================

    public function apiList()
    {
        $filters = request_all();

        $result = $this->orderRepository->getList($filters);

        return Response::json([
            'success' => true,

            'message' => 'Lấy danh sách đơn hàng thành công',

            'data' => $result,
        ]);
    }

    // =========================
    // SHOW
    // =========================

    public function apiShow()
    {
        $id = request_id();

        $data = $this->orderRepository->show($id);

        if (!$data) {
            return Response::json([
                'success' => false,

                'message' => 'Không tìm thấy đơn hàng',

                'data' => null,
            ]);
        }

        return Response::json([
            'success' => true,

            'message' => 'Lấy chi tiết đơn hàng thành công',

            'data' => $data,
        ]);
    }

    // =========================
    // CREATE
    // =========================

    public function apiCreate()
    {
        $input = request_all();

        $error = OrderValidator::create($input);

        if ($error) {
            return Response::json([
                'success' => false,

                'message' => $error,
            ]);
        }

        $input['created_by'] = Auth::id();

        $id = $this->orderRepository->create($input);

        return Response::json([
            'success' => true,

            'message' => 'Tạo đơn hàng thành công',

            'data' => [
                'id' => $id,
            ],

            'redirect' => '/admin/orders',
        ]);
    }

    // =========================
    // UPDATE
    // =========================

    public function apiUpdate()
    {
        $input = request_all();

        $error = OrderValidator::update($input);

        if ($error) {
            return Response::json([
                'success' => false,

                'message' => $error,
            ]);
        }

        $input['updated_by'] = Auth::id();

        $this->orderRepository->update($input);

        return Response::json([
            'success' => true,

            'message' => 'Cập nhật đơn hàng thành công',

            'redirect' => '/admin/orders',
        ]);
    }

    // =========================
    // DELETE
    // =========================

    public function apiDelete()
    {
        $id = request_id();

        $this->orderRepository->delete($id);

        return Response::json([
            'success' => true,

            'message' => 'Xoá đơn hàng thành công',
        ]);
    }

    // =========================
    // STATUS
    // =========================

    public function apiStatus()
    {
        $input = request_all();

        if (empty($input['id']) || !isset($input['status'])) {
            return Response::json([
                'success' => false,

                'message' => 'Thiếu dữ liệu trạng thái',
            ]);
        }

        $updated = $this->orderRepository->updateById((int) $input['id'], [
            'status' => $input['status'],
        ]);

        return Response::json([
            'success' => true,

            'message' => 'Cập nhật trạng thái thành công',

            'data' => [
                'affected_rows' => $updated,
            ],
        ]);
    }

    // =========================
    // PAYMENT
    // =========================

    public function apiPayment()
    {
        $input = request_all();

        if (empty($input['id']) || !isset($input['payment'])) {
            return Response::json([
                'success' => false,

                'message' => 'Thiếu dữ liệu thanh toán',
            ]);
        }

        $updated = $this->orderRepository->payment((int) $input['id'], $input['payment']);

        return Response::json([
            'success' => true,

            'message' => 'Cập nhật thanh toán thành công',

            'data' => [
                'affected_rows' => $updated,
            ],
        ]);
    }
}
