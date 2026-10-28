<?php

$products = $result[0] ?? [];
$products = is_array($products) ? $products : [];
$day = (int) date('j');
$month = (int) date('n');
$year = date('Y');

$saleProducts = array_map(static function (array $product) use ($profitPercent): array {
    $price = (float) ($product['price'] ?? 0);
    $importPrice = (float) ($product['import_price'] ?? 0);
    $originalProfit = max(0, $price - $importPrice);
    $salePrice = round($importPrice + $originalProfit * $profitPercent / 100);

    return [
        'name' => (string) ($product['name'] ?? ''),
        'category_id' => (int) ($product['category_id'] ?? 0),
        'category_name' => (string) ($product['category_name'] ?? ''),
        'price' => $price,
        'sale_price' => $salePrice,
    ];
}, $products);

$saleProducts = array_values(array_filter(
    $saleProducts,
    static fn (array $product): bool => $product['sale_price'] < $product['price'],
));

$displaySaleProducts = array_slice($saleProducts, 0, $productLimit);
$productCount = count($displaySaleProducts);
$dateTitle = sprintf('%02d/%02d/%s', $day, $month, $year);

?>

<main class="container py-4 sale-page">
    <header class="sale-banner rounded-4 p-4 p-lg-5 mb-4 text-white">
        <div class="row align-items-center g-4">
            <div class="col-lg">
                <span class="badge rounded-pill bg-warning text-dark mb-3">ƯU ĐÃI CÓ HẠN</span>
                <h1 class="display-5 fw-bold mb-2">Khuyến mãi ngày <?= htmlspecialchars($dateTitle, ENT_QUOTES, 'UTF-8') ?></h1>
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

    <div class="table-responsive bg-white border rounded-3 shadow-sm">
        <table class="table table-hover align-middle mb-0 sale-list">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Mặt hàng</th>
                    <th class="text-end" scope="col">Giá bán gốc</th>
                    <th class="text-end" scope="col">Giá khuyến mãi</th>
                </tr>
            </thead>
            <tbody id="sale-product-list">
                <?php foreach ($displaySaleProducts as $index => $product): ?>
                    <tr>
                        <td class="text-secondary"><?= $index + 1 ?></td>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></div>
                            <small class="text-secondary"><?= htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8') ?></small>
                        </td>
                        <td class="text-end text-nowrap"><?= number_format($product['price'], 0, ',', '.') ?>₫</td>
                        <td class="text-end fw-bold text-danger text-nowrap"><?= number_format($product['sale_price'], 0, ',', '.') ?>₫</td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($productCount === 0): ?>
                    <tr>
                        <td class="text-center text-secondary py-4" colspan="4">Không có mặt hàng khuyến mãi phù hợp với bộ lọc.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<style>
    .sale-banner {
        background: linear-gradient(135deg, #2f3e2c 0%, #69a84f 55%, #3f6f32 100%);
    }

    .sale-list th {
        white-space: nowrap;
    }

    @media (min-width: 768px) {
        .sale-list th,
        .sale-list td {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
</style>

<script id="sale-poster-data" type="application/json" data-day="<?= $day ?>" data-month="<?= $month ?>" data-year="<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>" data-limit="<?= $productLimit ?>"><?= json_encode($displaySaleProducts, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
