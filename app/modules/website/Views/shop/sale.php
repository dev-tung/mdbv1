<?php

$products = $result[0] ?? [];
$products = is_array($products) ? $products : [];
$month = (int) date('n');
$year = date('Y');

$saleProducts = array_map(static function (array $product): array {
    return [
        'name' => (string) ($product['name'] ?? ''),
        'category_id' => (int) ($product['category_id'] ?? 0),
        'category_name' => (string) ($product['category_name'] ?? ''),
        'price' => (float) ($product['price'] ?? 0),
        'import_price' => (float) ($product['import_price'] ?? 0),
    ];
}, $products);

$initialProducts = array_values(array_filter(
    array_map(static function (array $product) use ($profitPercent): array {
        $originalProfit = max(0, $product['price'] - $product['import_price']);
        $product['sale_price'] = round($product['import_price'] + $originalProfit * $profitPercent / 100);
        $product['discount_percent'] = $product['price'] > 0
            ? (int) round(($product['price'] - $product['sale_price']) / $product['price'] * 100)
            : 0;

        return $product;
    }, $saleProducts),
    static fn (array $product): bool => $product['sale_price'] < $product['price'],
));

$productCount = count($initialProducts);
$monthTitle = 'tháng ' . $month . ' năm ' . $year;
$categoriesById = $categoriesById ?? [];

?>

<main class="container py-4 sale-page">
    <header class="sale-banner rounded-4 p-4 p-lg-5 mb-4 text-white">
        <div class="row align-items-center g-4">
            <div class="col-lg">
                <span class="badge rounded-pill bg-warning text-dark mb-3">ƯU ĐÃI CÓ HẠN</span>
                <h1 class="display-5 fw-bold mb-2">Khuyến mãi <?= htmlspecialchars($monthTitle, ENT_QUOTES, 'UTF-8') ?></h1>
            </div>
            <div class="col-lg-auto">
                <button
                    class="btn btn-warning btn-lg fw-semibold"
                    id="download-sale-poster"
                    type="button"
                    <?= $productCount === 0 ? 'disabled' : '' ?>
                >
                    Tải ảnh khuyến mãi
                </button>
                <div id="sale-poster-status" class="small mt-2 text-lg-end" role="status" aria-live="polite"></div>
            </div>
        </div>
    </header>

    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
        <div>
            <h2 class="h4 fw-bold mb-1">Mặt hàng đang giảm giá</h2>
            <p class="text-secondary mb-0"><span id="sale-count"><?= $productCount ?></span> sản phẩm phù hợp.</p>
        </div>
    </div>

    <section class="card border-0 shadow-sm mb-3" aria-label="Bộ lọc khuyến mãi">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold" for="sale-filter-category">Danh mục</label>
                    <select class="form-select" id="sale-filter-category">
                        <option value="">Tất cả danh mục</option>
                        <?php foreach ($categoriesById as $categoryId => $categoryName): ?>
                            <option value="<?= (int) $categoryId ?>"><?= htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <div class="table-responsive bg-white border rounded-3 shadow-sm">
        <table class="table table-hover align-middle mb-0 sale-list">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Mặt hàng</th>
                    <th class="text-end" scope="col">Giá bán gốc</th>
                    <th class="text-end" scope="col">Giá khuyến mãi</th>
                    <th class="text-center" scope="col">Giảm</th>
                </tr>
            </thead>
            <tbody id="sale-product-list">
                <?php foreach ($initialProducts as $index => $product): ?>
                    <tr>
                        <td class="text-secondary"><?= $index + 1 ?></td>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></div>
                            <small class="text-secondary"><?= htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8') ?></small>
                        </td>
                        <td class="text-end text-nowrap"><?= number_format($product['price'], 0, ',', '.') ?>₫</td>
                        <td class="text-end fw-bold text-danger text-nowrap"><?= number_format($product['sale_price'], 0, ',', '.') ?>₫</td>
                        <td class="text-center"><span class="badge text-bg-danger">-<?= $product['discount_percent'] ?>%</span></td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($productCount === 0): ?>
                    <tr>
                        <td class="text-center text-secondary py-4" colspan="5">Không có mặt hàng khuyến mãi phù hợp với bộ lọc.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<style>
    .sale-banner {
        background: linear-gradient(120deg, #123f2b, #27834b);
    }

    .sale-list th {
        white-space: nowrap;
    }
</style>

<script id="sale-poster-data" type="application/json" data-month="<?= $month ?>" data-year="<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>" data-profit="<?= htmlspecialchars((string) $profitPercent, ENT_QUOTES, 'UTF-8') ?>"><?= json_encode($saleProducts, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
