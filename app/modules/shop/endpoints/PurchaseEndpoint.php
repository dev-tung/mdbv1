<?php

class PurchaseEndpoint
{
    private PurchaseService $purchaseService;
    private PurchaseRepository $purchaseRepository;

    public function __construct()
    {
        $this->purchaseService    = new PurchaseService();
        $this->purchaseRepository = new PurchaseRepository();
    }

    // =========================
    // LIST
    // =========================
    public function apiList()
    {
        $input = request_all();
        $result = $this->purchaseService->getList($input);

        return Response::json([
            'success' => true,
            'code'    => 'PURCHASE_LIST_SUCCESS',
            'message' => 'Lấy danh sách phiếu nhập thành công',
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

        $data = $this->purchaseService->show($id);

        if (!$data) {
            return Response::json([
                'success' => false,
                'code'    => 'PURCHASE_NOT_FOUND',
                'message' => 'Không tìm thấy phiếu nhập',
                'data'    => null
            ]);
        }

        return Response::json([
            'success' => true,
            'code'    => 'PURCHASE_SHOW_SUCCESS',
            'message' => 'Lấy chi tiết phiếu nhập thành công',
            'data'    => $data
        ]);
    }

    // =========================
    // CREATE
    // =========================
    public function apiCreate()
    {
        $input = request_all();

        $error = PurchaseValidator::create($input);

        if ($error) {
            return Response::json([
                'success' => false,
                'code'    => 'PURCHASE_CREATE_VALIDATE_ERROR',
                'message' => $error
            ]);
        }

        $id = $this->purchaseService->create($input);

        return Response::json([
            'success' => true,
            'code'    => 'PURCHASE_CREATED',
            'message' => 'Tạo phiếu nhập thành công',
            'data'    => [
                'id' => $id
            ],
            'redirect' => "/admin/purchases"
        ]);
    }

    // =========================
    // UPDATE
    // =========================
    public function apiUpdate()
    {
        $input = request_all();

        $error = PurchaseValidator::update($input);

        if ($error) {
            return Response::json([
                'success' => false,
                'code'    => 'PURCHASE_UPDATE_VALIDATE_ERROR',
                'message' => $error 
            ]);
        }

        $this->purchaseService->update($input);

        return Response::json([
            'success' => true,
            'code'    => 'PURCHASE_UPDATED',
            'message' => 'Cập nhật phiếu nhập thành công',
            'redirect' => "/admin/purchases"
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function apiDelete()
    {
        $id = request_id();

        $this->purchaseService->delete($id);

        return Response::json([
            'success' => true,
            'code'    => 'PURCHASE_DELETED',
            'message' => 'Xoá phiếu nhập thành công'
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

        $updated = $this->purchaseRepository->updateById(
            (int)$input['id'],
            ['status' => $input['status']]
        );

        return Response::json([
            'success' => true,
            'code'    => 'PURCHASE_STATUS_UPDATED',
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

        $updated = $this->purchaseRepository->updateById(
            (int)$input['id'],
            ['payment' => $input['payment']]
        );

        return Response::json([
            'success' => true,
            'code'    => 'PURCHASE_PAYMENT_UPDATED',
            'message' => 'Cập nhật thanh toán thành công',
            'data'    => [
                'affected_rows' => $updated
            ]
        ]);
    }
}