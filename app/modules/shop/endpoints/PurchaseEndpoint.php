<?php

class PurchaseEndpoint
{
    private PurchaseService $purchaseService;
    private PurchaseRepository $purchaseRepository;

    public function __construct()
    {
        $this->purchaseService     = new PurchaseService();
        $this->purchaseRepository  = new PurchaseRepository();
    }

    // =========================
    // LIST (DIRECT REPOSITORY)
    // =========================
    public function apiList()
    {
        $page  = request_input('page', 1);
        $limit = Config::get('pagination', 'default_per_page');
        $offset = ($page - 1) * $limit;

        $filters = request_filters([
            'keyword',
            'supplier_id',
            'status',
            'payment'
        ]);

        $data  = $this->purchaseRepository->getList($filters, $limit, $offset);
        $total = $this->purchaseRepository->count($filters);

        return Response::json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'page' => (int)$page,
                'perPage' => (int)$limit,
                'total' => $total,
                'totalPages' => $limit > 0 ? (int)ceil($total / $limit) : 0
            ]
        ]);
    }

    // =========================
    // SHOW (DIRECT REPOSITORY)
    // =========================
    public function apiShow()
    {
        $id = request_id();

        $data = $this->purchaseRepository->findDetail($id);

        if (!$data) {
            return Response::json([
                'success' => false,
                'message' => 'Purchase not found'
            ]);
        }

        return Response::json([
            'success' => true,
            'data' => $data
        ]);
    }

    // =========================
    // CREATE (SERVICE)
    // =========================
    public function apiCreate()
    {
        $input = request_all();

        $error = PurchaseValidator::create($input);

        if ($error) {
            return Response::json([
                'success' => false,
                'message' => $error
            ]);
        }

        $id = $this->purchaseService->create($input);

        return Response::json([
            'success' => true,
            'message' => 'Create success',
            'id' => $id
        ]);
    }

    // =========================
    // UPDATE (SERVICE)
    // =========================
    public function apiUpdate()
    {
        $id = request_id();
        $input = request_all();

        $input['id'] = $id;

        $error = PurchaseValidator::update($input);

        if ($error) {
            return Response::json([
                'success' => false,
                'message' => $error
            ]);
        }

        $this->purchaseService->update($input);

        return Response::json([
            'success' => true,
            'message' => 'Update success'
        ]);
    }

    // =========================
    // DELETE (SERVICE)
    // =========================
    public function apiDelete()
    {
        $id = request_id();

        $this->purchaseService->delete($id);

        return Response::json([
            'success' => true,
            'message' => 'Delete success'
        ]);
    }
}