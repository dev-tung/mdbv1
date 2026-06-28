<?php

class SupplierEndpoint
{
    protected SupplierModel $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    // =========================
    // LIST
    // =========================
    public function apiList()
    {
        header('Content-Type: application/json');

        $keyword = $_GET['keyword'] ?? '';

        $filters = [];

        if (!empty($keyword)) {
            $filters['keyword'] = $keyword;
        }

        $suppliers = $this->supplierModel->getList($filters);

        echo json_encode([
            'data' => $suppliers
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

        $supplier = $this->supplierModel->findById($id);

        if (!$supplier) {
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy nhà cung cấp'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => $supplier
        ]);
    }

    // =========================
    // CREATE
    // =========================
    public function apiCreate()
    {
        header('Content-Type: application/json');

        $data = [
            'name'        => trim($_POST['name'] ?? ''),
            'phone'       => trim($_POST['phone'] ?? ''),
            'email'       => trim($_POST['email'] ?? ''),
            'address'     => trim($_POST['address'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if ($data['name'] === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Tên nhà cung cấp không được để trống'
            ]);
            return;
        }

        $id = $this->supplierModel->create($data);

        echo json_encode([
            'success' => $id > 0,
            'message' => $id ? 'Tạo nhà cung cấp thành công' : 'Tạo thất bại',
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
            'name'        => trim($_POST['name'] ?? ''),
            'phone'       => trim($_POST['phone'] ?? ''),
            'email'       => trim($_POST['email'] ?? ''),
            'address'     => trim($_POST['address'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        $updated = $this->supplierModel->updateById($id, $data);

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

        $deleted = $this->supplierModel->deleteById($id);

        echo json_encode([
            'success' => $deleted > 0,
            'message' => $deleted > 0 ? 'Xóa thành công' : 'Không tìm thấy nhà cung cấp'
        ]);
    }
}