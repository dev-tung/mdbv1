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
            <section class="sale-poster" aria-label="Khuyến mãi ngày <?= htmlspecialchars($dateTitle, ENT_QUOTES, 'UTF-8') ?> tại MDB Shop">
                <h1 class="sale-poster-title">KHUYẾN MÃI NGÀY <?= htmlspecialchars($dateTitle, ENT_QUOTES, 'UTF-8') ?> TẠI MDB SHOP</h1>
                <ol class="sale-poster-products" style="--sale-product-count: <?= $productCount ?>">
                    <?php foreach ($displaySaleProducts as $index => $product): ?>
                        <li class="sale-poster-product">
                            <span class="sale-poster-number"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
                            <span class="sale-poster-name">
                                <span class="sale-poster-product-name"><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="sale-poster-category"><?= htmlspecialchars($product['category_name'] ?: 'Sản phẩm', ENT_QUOTES, 'UTF-8') ?></span>
                            </span>
                            <span class="sale-poster-prices">
                                <del><?= number_format($product['price'], 0, ',', '.') ?>₫</del>
                                <strong><?= number_format($product['sale_price'], 0, ',', '.') ?>₫</strong>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ol>
                <p class="sale-poster-contact">Liên hệ SĐT 0973359165 để đặt hàng</p>
            </section>
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

    .sale-poster {
        container-type: inline-size;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 100%;
        max-width: 640px;
        aspect-ratio: 9 / 16;
        padding: 9.6% 8.33% 7.8%;
        overflow: hidden;
        color: #fff;
        background: linear-gradient(135deg, #69a84f 0%, #3f7f35 100%);
        border-radius: 0.75rem;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.18);
    }

    .sale-poster-title {
        flex: 0 0 auto;
        margin: 0;
        color: inherit;
        font-size: clamp(14px, 4.26cqw, 28px);
        font-weight: 700;
        line-height: 1.2;
        text-align: center;
    }

    .sale-poster-products {
        display: grid;
        flex: 1 1 auto;
        grid-template-rows: repeat(var(--sale-product-count), minmax(0, 1fr));
        gap: 0.52cqw;
        min-height: 0;
        margin: 3.2cqw 0 0;
        padding: 0;
        list-style: none;
    }

    .sale-poster-product {
        display: grid;
        grid-template-columns: 5.7cqw minmax(0, 1fr) auto;
        align-items: center;
        gap: 1.8cqw;
        min-width: 0;
        padding: 0 2.2cqw;
        color: #19241d;
        background: #fff;
        border-radius: 1.5cqw;
    }

    .sale-poster-number {
        color: #69a84f;
        font-size: clamp(11px, 1.67cqw, 18px);
        font-weight: 700;
    }

    .sale-poster-name {
        display: flex;
        min-width: 0;
        flex-direction: column;
        gap: 0.5cqw;
        line-height: 1.2;
    }

    .sale-poster-product-name {
        overflow: hidden;
        font-size: clamp(10px, 1.95cqw, 21px);
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sale-poster-category {
        overflow: hidden;
        color: #68736c;
        font-size: clamp(9px, 1.45cqw, 16px);
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sale-poster-prices {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.4cqw;
        white-space: nowrap;
    }

    .sale-poster-prices del {
        color: #68736c;
        font-size: clamp(9px, 1.45cqw, 16px);
    }

    .sale-poster-prices strong {
        color: #c62828;
        font-size: clamp(11px, 2.2cqw, 24px);
    }

    .sale-poster-contact {
        flex: 0 0 auto;
        margin: 5cqw 0 0;
        color: inherit;
        font-size: clamp(16px, 5.6cqw, 36px);
        font-weight: 700;
        line-height: 1.2;
        text-align: center;
    }
</style>

<script id="sale-poster-data" type="application/json" data-day="<?= $day ?>" data-month="<?= $month ?>" data-year="<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>" data-limit="<?= $productLimit ?>"><?= json_encode($displaySaleProducts, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
