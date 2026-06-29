<?php

class WarehouseEndpoint
{
    protected WarehouseRepository $warehouseRepository;

    public function __construct()
    {
        $this->warehouseRepository = new WarehouseRepository();
    }

    // =========================
    // LIST (dropdown / full list)
    // =========================
    public function apiList()
    {
        header('Content-Type: application/json');

        $keyword = $_GET['keyword'] ?? '';

        $filters = [];

        if (!empty($keyword)) {
            $filters['keyword'] = $keyword;
        }

        $warehouses = $this->warehouseRepository->getList($filters);

        echo json_encode([
            'data' => $warehouses
        ]);
    }

    // =========================
    // SHOW
    // =========================
    public function apiShow($id)
    {
        header('Content-Type: application/json');

        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
            return;
        }

        $warehouse = $this->warehouseRepository->findById($id);

        if (!$warehouse) {
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy kho'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => $warehouse
        ]);
    }

    // =========================
    // CREATE
    // =========================
    public function apiCreate()
    {
        header('Content-Type: application/json');

        $data = [
            'name'       => trim($_POST['name'] ?? ''),
            'address'    => trim($_POST['address'] ?? ''),
            'status'     => $_POST['status'] ?? 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($data['name'] === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Tên kho không được để trống'
            ]);
            return;
        }

        $id = $this->warehouseRepository->create($data);

        echo json_encode([
            'success' => $id > 0,
            'message' => $id ? 'Tạo kho thành công' : 'Tạo thất bại',
            'id'      => $id
        ]);
    }

    // =========================
    // UPDATE
    // =========================
    public function apiUpdate()
    {
        header('Content-Type: application/json');

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
            return;
        }

        $data = [
            'name'       => trim($_POST['name'] ?? ''),
            'address'    => trim($_POST['address'] ?? ''),
            'status'     => $_POST['status'] ?? 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $updated = $this->warehouseRepository->updateById($id, $data);

        echo json_encode([
            'success' => $updated > 0,
            'message' => $updated > 0 ? 'Cập nhật thành công' : 'Không có thay đổi'
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function apiDelete()
    {
        header('Content-Type: application/json');

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
            return;
        }

        $deleted = $this->warehouseRepository->deleteById($id);

        echo json_encode([
            'success' => $deleted > 0,
            'message' => $deleted > 0 ? 'Xóa thành công' : 'Không tìm thấy kho'
        ]);
    }
}