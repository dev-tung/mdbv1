<?php

class ProductRepository
{
    /**
     * BUILD WHERE
     */
    private function buildWhere(array $conditions, array &$params): string
    {
        $sql = " WHERE 1=1";

        if (!empty($conditions['status'])) {
            $sql .= " AND p.status = :status";
            $params['status'] = $conditions['status'];
        }

        if (!empty($conditions['category_id'])) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $conditions['category_id'];
        }

        if (!empty($conditions['keyword'])) {
            $sql .= " AND p.name LIKE :keyword";
            $params['keyword'] = '%' . trim($conditions['keyword']) . '%';
        }

        if (!empty($conditions['brands']) && is_array($conditions['brands'])) {

            $placeholders = [];

            foreach ($conditions['brands'] as $index => $brandId) {

                $key = 'brand_' . $index;

                $placeholders[] = ':' . $key;

                $params[$key] = (int) $brandId;
            }

            if (!empty($placeholders)) {
                $sql .= " AND p.brand_id IN (" . implode(',', $placeholders) . ")";
            }
        }

        if (!empty($conditions['price'])) {

            $ranges = config('shop.option.price_range') ?? [];

            if (isset($ranges[$conditions['price']])) {

                $range = $ranges[$conditions['price']];

                if ($range['max'] === null) {

                    $sql .= " AND p.price >= :price_min";
                    $params['price_min'] = $range['min'];

                } else {

                    $sql .= " AND p.price BETWEEN :price_min AND :price_max";

                    $params['price_min'] = $range['min'];
                    $params['price_max'] = $range['max'];
                }
            }
        }

        return $sql;
    }

    /**
     * GET LIST
     */
    public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $params = [];

        $sql = "
            SELECT
                p.*,
                c.name AS category_name
            FROM products p
            LEFT JOIN categories c
                ON c.id = p.category_id
        ";

        $sql .= $this->buildWhere($conditions, $params);

        $sql .= " ORDER BY p.id DESC";

        if ($limit > 0) {

            $limit = (int) $limit;
            $offset = (int) $offset;

            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return Database::get($sql, $params);
    }

    /**
     * COUNT
     */
    public function count(array $conditions = []): int
    {
        $params = [];

        $sql = "
            SELECT COUNT(*) AS total
            FROM products p
        ";

        $sql .= $this->buildWhere($conditions, $params);

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }

    /**
     * FIND BY ID
     */
    public function findById(int $id): ?array
    {
        return Database::first(
            "
                SELECT *
                FROM products
                WHERE id = :id
                LIMIT 1
            ",
            ['id' => $id]
        );
    }

    /**
     * CREATE
     */
    public function create(array $data): int
    {
        $fields = array_keys($data);

        $columns = implode(', ', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "
            INSERT INTO products ({$columns})
            VALUES ({$placeholders})
        ";

        return Database::insert($sql, $data);
    }

    /**
     * UPDATE
     */
    public function updateById(int $id, array $data): int
    {
        if (empty($data)) {
            return 0;
        }

        $set = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }

        $data['id'] = $id;

        $sql = "
            UPDATE products
            SET " . implode(', ', $set) . "
            WHERE id = :id
        ";

        return Database::update($sql, $data);
    }

    /**
     * DELETE
     */
    public function deleteById(int $id): int
    {
        return Database::delete(
            "
                DELETE FROM products
                WHERE id = :id
            ",
            ['id' => $id]
        );
    }

    /**
     * FIND BY SLUG
     */
    public function findBySlug(string $slug): ?array
    {
        $product = Database::first(
            "
                SELECT
                    p.*,
                    c.name AS category_name,
                    b.name AS brand_name
                FROM products p
                LEFT JOIN categories c
                    ON c.id = p.category_id
                LEFT JOIN brands b
                    ON b.id = p.brand_id
                WHERE p.slug = :slug
                LIMIT 1
            ",
            [
                'slug' => $slug
            ]
        );

        if (!$product) {
            return null;
        }

        $product['gallery'] = array_column(
            Database::get(
                "
                    SELECT image
                    FROM product_images
                    WHERE product_id = :id
                    ORDER BY sort_order, id
                ",
                [
                    'id' => $product['id']
                ]
            ),
            'image'
        );

        $product['attributes'] = Database::get(
            "
                SELECT
                    attribute_name,
                    attribute_value
                FROM product_attributes
                WHERE product_id = :id
                ORDER BY id
            ",
            [
                'id' => $product['id']
            ]
        );

        return $product;
    }
}