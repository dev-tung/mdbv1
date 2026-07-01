<?php

class OrderEndpoint
{
    private OrderService $orderService;
    private OrderRepository $orderRepository;

    public function __construct()
    {
        $this->orderService    = new OrderService();
        $this->orderRepository = new OrderRepository();
    }

    // =========================
    // LIST
    // =========================
    public function apiList()
    {
        $input = request_all();
        $result = $this->orderService->getList($input);

        return Response::json([
            'success' => true,
            'code'    => 'ORDER_LIST_SUCCESS',
            'message' => 'Lấy danh sách đơn hàng thành công',
            'data'    => $result['data'],
            'meta'    => $result['meta'],
        ]);
    }

    // =========================
    // SHOW
    // =========================
    public function apiShow()
    {
        $id = request_id();

        $data = $this->orderService->show($id);

        if (!$data) {
            return Response::json([
                'success' => false,
                'code'    => 'ORDER_NOT_FOUND',
                'message' => 'Không tìm thấy đơn hàng',
                'data'    => null
            ]);
        }

        return Response::json([
            'success' => true,
            'code'    => 'ORDER_SHOW_SUCCESS',
            'message' => 'Lấy chi tiết đơn hàng thành công',
            'data'    => $data
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
                'code'    => 'ORDER_CREATE_VALIDATE_ERROR',
                'message' => $error
            ]);
        }

        $id = $this->orderService->create($input);

        return Response::json([
            'success' => true,
            'code'    => 'ORDER_CREATED',
            'message' => 'Tạo đơn hàng thành công',
            'data'    => [
                'id' => $id
            ],
            'redirect' => "/admin/orders"
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
                'code'    => 'ORDER_UPDATE_VALIDATE_ERROR',
                'message' => $error 
            ]);
        }

        $this->orderService->update($input);

        return Response::json([
            'success' => true,
            'code'    => 'ORDER_UPDATED',
            'message' => 'Cập nhật đơn hàng thành công',
            'redirect' => "/admin/orders"
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function apiDelete()
    {
        $id = request_id();

        $this->orderService->delete($id);

        return Response::json([
            'success' => true,
            'code'    => 'ORDER_DELETED',
            'message' => 'Xoá đơn hàng thành công'
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
                'code'    => 'INVALID_INPUT',
                'message' => 'Thiếu dữ liệu trạng thái'
            ]);
        }

        $updated = $this->orderRepository->updateById(
            (int)$input['id'],
            ['status' => $input['status']]
        );

        return Response::json([
            'success' => true,
            'code'    => 'ORDER_STATUS_UPDATED',
            'message' => 'Cập nhật trạng thái thành công',
            'data'    => [
                'affected_rows' => $updated
            ]
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
                'code'    => 'INVALID_INPUT',
                'message' => 'Thiếu dữ liệu thanh toán'
            ]);
        }

        $updated = $this->orderService->updatePayment(
            (int)$input['id'],
            ['payment' => $input['payment']]
        );

        return Response::json([
            'success' => true,
            'code'    => 'ORDER_PAYMENT_UPDATED',
            'message' => 'Cập nhật thanh toán thành công',
            'data'    => [
                'affected_rows' => $updated
            ]
        ]);
    }
}