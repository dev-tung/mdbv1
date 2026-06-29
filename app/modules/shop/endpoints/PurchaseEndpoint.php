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
    // LIST (SERVICE)
    // =========================
    public function apiList()
    {
        $input = request_all();

        $result = $this->purchaseService->getList($input);

        return Response::json([
            'success' => true,
            'data'    => $result['data'],
            'meta'    => $result['meta'],
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
            'data'    => $data
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
            'id'      => $id
        ]);
    }

    // =========================
    // UPDATE (SERVICE)
    // =========================
    public function apiUpdate()
    {
        $input = request_all();

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

    // =========================
    // UPDATE STATUS
    // =========================
    public function apiStatus()
    {
        $input = request_all();

        if (empty($input['id']) || !isset($input['status'])) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid input'
            ]);
        }

        $updated = $this->purchaseRepository->updateById(
            (int)$input['id'],
            ['status' => $input['status']]
        );

        return Response::json([
            'success' => true,
            'message' => 'Status updated successfully',
            'affected_rows' => $updated
        ]);
    }

    // =========================
    // UPDATE PAYMENT
    // =========================
    public function apiPayment()
    {
        $input = request_all();

        if (empty($input['id']) || !isset($input['payment'])) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid input'
            ]);
        }

        $updated = $this->purchaseRepository->updateById(
            (int)$input['id'],
            ['payment' => $input['payment']]
        );

        return Response::json([
            'success' => true,
            'message' => 'Payment updated successfully',
            'affected_rows' => $updated
        ]);
    }
}