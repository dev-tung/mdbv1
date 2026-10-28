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
    <div class="sale-actions">
        <button
            class="btn btn-success fw-semibold"
            id="download-sale-poster"
            type="button"
            <?= $productCount === 0 ? 'disabled' : '' ?>
        >
            Tải ảnh khuyến mãi
        </button>
        <div id="sale-poster-status" class="small mt-2" role="status" aria-live="polite"></div>
    </div>

    <?php if ($productCount > 0): ?>
        <div class="sale-poster-wrap">
            <canvas
                class="sale-poster-preview"
                id="sale-poster-preview"
                width="1080"
                height="1920"
                aria-label="Khuyến mãi ngày <?= htmlspecialchars($dateTitle, ENT_QUOTES, 'UTF-8') ?> tại MDB Shop"
            ></canvas>
        </div>
    <?php else: ?>
        <p class="text-center text-secondary py-4">Không có mặt hàng khuyến mãi phù hợp với bộ lọc.</p>
    <?php endif; ?>
</main>

<style>
    .sale-actions {
        max-width: 432px;
        margin: 0 auto 1rem;
    }

    .sale-poster-wrap {
        display: flex;
        justify-content: center;
    }

    .sale-poster-preview {
        display: block;
        width: 100%;
        max-width: 432px;
        height: auto;
        border-radius: 0.75rem;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.18);
    }
</style>

<script id="sale-poster-data" type="application/json" data-day="<?= $day ?>" data-month="<?= $month ?>" data-year="<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>" data-limit="<?= $productLimit ?>"><?= json_encode($displaySaleProducts, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
