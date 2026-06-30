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

        $data = $this->purchaseRepository->findDetail($id);

        if (!$data) {
            return Response::json([
                'success' => false,
                'code'    => 'PURCHASE_NOT_FOUND',
                'data'    => null
            ]);
        }

        return Response::json([
            'success' => true,
            'code'    => 'PURCHASE_SHOW_SUCCESS',
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
                'code'    => 'PURCHASE_CREATE_VALIDATE_ERROR'
            ]);
        }

        $id = $this->purchaseService->create($input);

        return Response::json([
            'success' => true,
            'code'    => 'PURCHASE_CREATED',
            'data'    => [
                'id' => $id
            ]
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
                'code'    => 'PURCHASE_UPDATE_VALIDATE_ERROR'
            ]);
        }

        $this->purchaseService->update($input);

        return Response::json([
            'success' => true,
            'code'    => 'PURCHASE_UPDATED'
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
            'code'    => 'PURCHASE_DELETED'
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
                'code'    => 'INVALID_INPUT'
            ]);
        }

        $updated = $this->purchaseRepository->updateById(
            (int)$input['id'],
            ['status' => $input['status']]
        );

        return Response::json([
            'success' => true,
            'code'    => 'PURCHASE_STATUS_UPDATED',
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
                'code'    => 'INVALID_INPUT'
            ]);
        }

        $updated = $this->purchaseRepository->updateById(
            (int)$input['id'],
            ['payment' => $input['payment']]
        );

        return Response::json([
            'success' => true,
            'code'    => 'PURCHASE_PAYMENT_UPDATED',
            'data'    => [
                'affected_rows' => $updated
            ]
        ]);
    }
}