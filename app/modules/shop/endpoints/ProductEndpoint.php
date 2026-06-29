<?php

class ProductEndpoint
{
    protected ProductRepository $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    // =========================
    // LIST
    // =========================
    public function apiList()
    {
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = Config::get('pagination', 'default_per_page');

        $filters = request_filters([
            'keyword',
            'category_id',
            'status',
            'price'
        ]);

        $filters['brands'] = $_GET['brand'] ?? [];

        $products = $this->productRepository->getList(
            $filters,
            $limit,
            ($page - 1) * $limit
        );

        $total = $this->productRepository->count($filters);

        return Response::json([
            'data' => $products,
            'meta' => [
                'page'       => $page,
                'total'      => $total,
                'totalPages' => ceil($total / $limit),
                'perPage'    => $limit
            ]
        ]);
    }

    // =========================
    // SHOW
    // =========================
    public function apiShow($id )
    {
        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        $product = $this->productRepository->findById($id);

        if (!$product) {
            return Response::json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ]);
        }

        return Response::json([
            'success' => true,
            'data' => $product
        ]);
    }

 // =========================
// CREATE
// =========================
public function apiCreate()
{
    $price = (float)($_POST['price'] ?? 0);
    $salePrice = (float)($_POST['sale_price'] ?? 0);

    if ($salePrice <= 0) {
        $salePrice = $price;
    }

    $data = [
        'name'        => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'category_id' => (int)($_POST['category_id'] ?? 0),
        'price'       => $price,
        'sale_price'  => $salePrice,
        'status'      => (int)($_POST['status'] ?? 1),
        'thumbnail'   => null
    ];

    if ($data['name'] === '') {
        return Response::json([
            'success' => false,
            'message' => 'Tên sản phẩm không được để trống'
        ]);
    }

    // UPLOAD THUMBNAIL
    if (!empty($_FILES['thumbnail']['name'])) {

        $file = $_FILES['thumbnail'];

        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (in_array($file['type'], $allowed)) {

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('thumb_') . '.' . $ext;

            $uploadDir = PATH_ROOT . '/public/uploads/products/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $data['thumbnail'] = '/uploads/products/' . $fileName;
            }
        }
    }

    $id = $this->productRepository->create($data);

    return Response::json([
        'success' => $id > 0,
        'message' => $id ? 'Tạo sản phẩm thành công' : 'Tạo thất bại',
        'id'      => $id
    ]);
}


// =========================
// UPDATE
// =========================
public function apiUpdate()
{
    $id = (int)($_POST['id'] ?? 0);

    if ($id <= 0) {
        return Response::json([
            'success' => false,
            'message' => 'ID không hợp lệ'
        ]);
    }

    $price = (float)($_POST['price'] ?? 0);
    $salePrice = (float)($_POST['sale_price'] ?? 0);

    if ($salePrice <= 0) {
        $salePrice = $price;
    }

    $data = [
        'name'        => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'category_id' => (int)($_POST['category_id'] ?? 0),
        'price'       => $price,
        'sale_price'  => $salePrice,
        'status'      => (int)($_POST['status'] ?? 1),
    ];

    if ($data['name'] === '') {
        return Response::json([
            'success' => false,
            'message' => 'Tên sản phẩm không được để trống'
        ]);
    }

    // =========================
    // UPLOAD THUMBNAIL
    // =========================
    if (!empty($_FILES['thumbnail']['name'])) {

        $file = $_FILES['thumbnail'];

        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (in_array($file['type'], $allowed)) {

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('thumb_') . '.' . $ext;

            $uploadDir = PATH_ROOT . '/public/uploads/products/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $data['thumbnail'] = '/uploads/products/' . $fileName;
            }
        }
    }

    $updated = $this->productRepository->updateById($id, $data);

    return Response::json([
        'success' => $updated > 0,
        'message' => $updated > 0
            ? 'Cập nhật thành công'
            : 'Không có thay đổi'
    ]);
}
    // =========================
    // DELETE
    // =========================
    public function apiDelete()
    {
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        $deleted = $this->productRepository->deleteById($id);

        return Response::json([
            'success' => $deleted > 0,
            'message' => $deleted > 0 ? 'Xóa thành công' : 'Không tìm thấy sản phẩm'
        ]);
    }
}