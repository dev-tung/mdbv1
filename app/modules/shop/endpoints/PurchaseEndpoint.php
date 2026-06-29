<?php

class PurchaseEndpoint
{
    private PurchaseModel $purchaseModel;

    public function __construct()
    {
        $this->purchaseModel = new PurchaseModel();
    }

    // =========================
    // LIST
    // =========================
    public function apiList()
    {
        $page  = request_input('page', 1);
        $limit = Config::get('pagination', 'default_per_page');

        $filters = request_filters([
            'keyword',
            'supplier_id',
            'status',
            'payment'
        ]);

        $offset = ($page - 1) * $limit;

        $data = $this->purchaseModel->getList($filters, $limit, $offset);
        $total = $this->purchaseModel->count($filters);

        return Response::json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'page' => $page,
                'total' => $total,
                'totalPages' => $limit > 0 ? ceil($total / $limit) : 0,
                'perPage' => $limit
            ]
        ]);
    }

    // =========================
    // SHOW
    // =========================
    public function apiShow()
    {
        $id = request_id();

        $data = $this->purchaseModel->findDetail($id);

        if (!$data) {
            return Response::json([
                'success' => false,
                'message' => 'Không tìm thấy phiếu nhập'
            ]);
        }

        return Response::json([
            'success' => true,
            'data' => $data
        ]);
    }

    // =========================
    // CREATE
    // =========================
    public function apiCreate()
    {
        $input = request_all();

        $error = PurchaseValidator::validateCreate($input);

        if ($error) {
            return Response::json([
                'success' => false,
                'message' => $error
            ]);
        }

        $id = $this->purchaseModel->createPurchase($input);

        return Response::json([
            'success' => true,
            'message' => 'Tạo phiếu nhập thành công',
            'id' => $id
        ]);
    }

    // =========================
    // UPDATE
    // =========================
    public function apiUpdate()
    {
        $id = request_id();

        $input = request_all();

        $error = PurchaseValidator::validateUpdate($input);

        if ($error) {
            return Response::json([
                'success' => false,
                'message' => $error
            ]);
        }

        $this->purchaseModel->updatePurchase($id, $input);

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
        $id = request_id();

        $this->purchaseModel->deletePurchase($id);

        return Response::json([
            'success' => true,
            'message' => 'Xóa thành công'
        ]);
    }
}