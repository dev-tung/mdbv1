<?php

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    // =========================
    // LIST
    // =========================
    public function getList(array $input): array
    {
        $page  = max(1, (int)($input['page'] ?? 1));
        $limit = Config::get('pagination', 'default_per_page');
        $offset = ($page - 1) * $limit;

        $filters = request_filters([
            'keyword',
            'category_id',
            'status',
            'price'
        ]);

        $filters['brands'] = $input['brand'] ?? [];

        $data = $this->productRepository->getList($filters, $limit, $offset);
        $total = $this->productRepository->count($filters);

        return [
            'data' => $data,
            'meta' => [
                'page'       => $page,
                'total'      => $total,
                'totalPages' => ceil($total / $limit),
                'perPage'    => $limit
            ]
        ];
    }

    // =========================
    // CREATE
    // =========================
    public function create(array $input, array $file = []): int
    {
        $price = (float)($input['price'] ?? 0);
        $sale  = (float)($input['sale_price'] ?? 0);

        if ($sale <= 0) {
            $sale = $price;
        }

        $data = [
            'name'        => trim($input['name']),
            'description' => trim($input['description'] ?? ''),
            'category_id' => (int)$input['category_id'],
            'price'       => $price,
            'sale_price'  => $sale,
            'status'      => (int)($input['status'] ?? 1),
            'thumbnail'   => null,
        ];

        // upload thumbnail
        if (!empty($file['name'])) {
            $data['thumbnail'] = $this->uploadThumbnail($file);
        }

        return $this->productRepository->create($data);
    }

    // =========================
    // UPDATE
    // =========================
    public function update(int $id, array $input, array $file = []): bool
    {
        $price = (float)($input['price'] ?? 0);
        $sale  = (float)($input['sale_price'] ?? 0);

        if ($sale <= 0) {
            $sale = $price;
        }

        $data = [
            'name'        => trim($input['name']),
            'description' => trim($input['description'] ?? ''),
            'category_id' => (int)$input['category_id'],
            'price'       => $price,
            'sale_price'  => $sale,
            'status'      => (int)($input['status'] ?? 1),
        ];

        // upload optional
        $thumbnail = $this->uploadThumbnail($file);
        if ($thumbnail) {
            $data['thumbnail'] = $thumbnail;
        }

        return $this->productRepository->updateById($id, $data) > 0;
    }

    // =========================
    // DELETE
    // =========================
    public function delete(int $id): bool
    {
        return $this->productRepository->deleteById($id) > 0;
    }

    // =========================
    // UPLOAD THUMBNAIL
    // =========================
    private function uploadThumbnail(array $file): ?string
    {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (empty($file['name']) || !in_array($file['type'], $allowed)) {
            return null;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('thumb_') . '.' . $ext;

        $uploadDir = PATH_ROOT . '/public/uploads/products/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $target = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            return '/uploads/products/' . $fileName;
        }

        return null;
    }
}