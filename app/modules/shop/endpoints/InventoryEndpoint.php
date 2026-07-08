<?php

class InventoryEndpoint
{
    protected InventoryRepository $inventoryRepository;

    public function __construct()
    {
        $this->inventoryRepository = new InventoryRepository();
    }

    // LIST
    public function apiList()
    {
        $filters = request_filters(['keyword', 'category_id', 'status', 'stock']);

        return Response::json([
            'success' => true,
            'data' => $this->inventoryRepository->getList($filters),
        ]);
    }

    // LIST
    public function apiStock()
    {
        $filters = request_filters(['keyword']);

        return Response::json([
            'success' => true,
            'data' => $this->inventoryRepository->getStock($filters),
        ]);
    }

    // SHOW
    public function apiShow($id)
    {
        $data = $this->inventoryRepository->apiShow($id);

        if (!$data) {
            return Response::json(
                [
                    'success' => false,
                    'message' => 'Inventory not found',
                ],
                404,
            );
        }

        return Response::json([
            'success' => true,
            'data' => $data,
        ]);
    }

    // CREATE
    public function apiCreate()
    {
        $data = request()->all();

        $id = $this->inventoryRepository->create([
            'product_id' => $data['product_id'],
            'warehouse_id' => $data['warehouse_id'] ?? null,
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'note' => $data['note'] ?? null,
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Created',
            'id' => $id,
        ]);
    }

    // UPDATE
    public function apiUpdate()
    {
        $data = request()->all();

        $this->inventoryRepository->update($data['id'], [
            'product_id' => $data['product_id'],
            'warehouse_id' => $data['warehouse_id'] ?? null,
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'note' => $data['note'] ?? null,
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Updated',
        ]);
    }

    // DELETE
    public function apiDelete()
    {
        $data = request()->all();

        $this->inventoryRepository->delete($data['id']);

        return Response::json([
            'success' => true,
            'message' => 'Deleted',
        ]);
    }
}
