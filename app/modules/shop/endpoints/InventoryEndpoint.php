<?php

class InventoryEndpoint
{

    protected InventoryModel $inventoryModel;


    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
    }


    // LIST
    public function apiList()
    {
        $filters = request_filters([
            'keyword',
            'category_id',
            'status'
        ]);

        return Response::json([
            'success'=>true,
            'data'=>$this->inventoryModel->getList($filters)
        ]);
    }

    // STOCK
    public function apiStock()
    {
        $filters = request_filters([
            'keyword',
            'category_id',
            'status'
        ]);

        return Response::json([
            'success'=>true,
            'data'=>$this->inventoryModel->getStock($filters)
        ]);
    }


    // SHOW
    public function apiShow($id)
    {
        $data = $this->inventoryModel->apiShow($id);

        if(!$data){

            return Response::json([
                'success'=>false,
                'message'=>'Inventory not found'
            ],404);

        }

        return Response::json([
            'success'=>true,
            'data'=>$data
        ]);
    }


    // CREATE
    public function apiCreate()
    {
        $data = request()->all();

        $id = $this->inventoryModel->create([
            'product_id'     => $data['product_id'],
            'warehouse_id'   => $data['warehouse_id'] ?? null,
            'type'           => $data['type'],
            'quantity'       => $data['quantity'],
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id'   => $data['reference_id'] ?? null,
            'note'           => $data['note'] ?? null,
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Created',
            'id'      => $id,
        ]);
    }

    // UPDATE
    public function apiUpdate()
    {
        $data = request()->all();

        $this->inventoryModel->update($data['id'], [
            'product_id'   => $data['product_id'],
            'warehouse_id' => $data['warehouse_id'] ?? null,
            'type'         => $data['type'],
            'quantity'     => $data['quantity'],
            'note'         => $data['note'] ?? null,
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

        $this->inventoryModel->delete($data['id']);

        return Response::json([
            'success'=>true,
            'message'=>'Deleted'
        ]);
    }

}