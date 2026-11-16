<?php
$hasDiscount = (float) ($product['sale_price'] ?? 0) > 0
    && (float) $product['sale_price'] < (float) $product['price'];
$displayPrice = $hasDiscount ? (float) $product['sale_price'] : (float) $product['price'];
?>

<main class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/product" class="text-decoration-none">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <img
                    id="product-image"
                    src="<?= htmlspecialchars($product['thumbnail'] ?: '/assets/image/no-image.svg') ?>"
                    class="card-img-top p-3"
                    alt="<?= htmlspecialchars($product['name']) ?>"
                >
                <?php if ($images): ?>
                    <div class="card-body d-flex flex-wrap gap-2">
                        <?php foreach ($images as $image): ?>
                            <button
                                type="button"
                                class="btn btn-outline-secondary p-1"
                                data-product-image="<?= htmlspecialchars($image['image']) ?>"
                                aria-label="Xem ảnh sản phẩm"
                            >
                                <img src="<?= htmlspecialchars($image['image']) ?>" width="64" height="64" alt="">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-secondary mb-2">
                        <?= htmlspecialchars($product['brand_name'] ?? 'Manh Dung Sports') ?>
                        <?php if (!empty($product['category_name'])): ?>
                            <span class="mx-1">·</span><?= htmlspecialchars($product['category_name']) ?>
                        <?php endif; ?>
                    </p>
                    <h1 class="h3 fw-bold"><?= htmlspecialchars($product['name']) ?></h1>

                    <div class="mb-3">
                        <?php if ($hasDiscount): ?>
                            <span class="fs-4 fw-bold text-danger">
                                <?= number_format((float) $product['sale_price'], 0, ',', '.') ?>₫
                            </span>
                            <span class="text-secondary text-decoration-line-through ms-2">
                                <?= number_format((float) $product['price'], 0, ',', '.') ?>₫
                            </span>
                        <?php else: ?>
                            <span class="fs-4 fw-bold text-danger">
                                <?= number_format((float) $product['price'], 0, ',', '.') ?>₫
                            </span>
                        <?php endif; ?>
                    </div>

                    <p class="mb-3">
                        <?php if ((int) ($product['stock'] ?? 0) > 0): ?>
                            <span class="badge text-bg-success">Còn hàng</span>
                            <span class="text-secondary ms-2">Còn <?= (int) $product['stock'] ?> sản phẩm</span>
                        <?php else: ?>
                            <span class="badge text-bg-secondary">Tạm hết hàng</span>
                        <?php endif; ?>
                    </p>

                    <?php if ($attributes): ?>
                        <div class="mb-3">
                            <label for="product-variant" class="form-label fw-semibold">Phiên bản / thuộc tính</label>
                            <select id="product-variant" class="form-select">
                                <?php foreach ($attributes as $attribute): ?>
                                    <option value="<?= htmlspecialchars($attribute['attribute_name'] . ': ' . $attribute['attribute_value']) ?>">
                                        <?= htmlspecialchars($attribute['attribute_name']) ?>:
                                        <?= htmlspecialchars($attribute['attribute_value']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label for="product-quantity" class="form-label fw-semibold">Số lượng</label>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button" id="quantity-decrease" aria-label="Giảm số lượng">−</button>
                            <input
                                id="product-quantity"
                                class="form-control text-center"
                                type="number"
                                min="1"
                                max="<?= max(1, (int) ($product['stock'] ?? 0)) ?>"
                                value="1"
                            >
                            <button class="btn btn-outline-secondary" type="button" id="quantity-increase" aria-label="Tăng số lượng">+</button>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-sm-flex">
                        <button
                            type="button"
                            id="add-product-to-cart"
                            class="btn btn-success btn-lg flex-grow-1"
                            <?= (int) ($product['stock'] ?? 0) < 1 ? 'disabled' : '' ?>
                        >
                            Thêm vào giỏ hàng
                        </button>
                        <a href="/cart" class="btn btn-outline-success btn-lg">Xem giỏ hàng</a>
                    </div>

                    <div class="alert alert-light border mt-4 mb-0">
                        <h2 class="h6 fw-bold">Mô tả sản phẩm</h2>
                        <div class="text-break"><?= nl2br(htmlspecialchars($product['description'] ?? 'Thông tin chi tiết đang được cập nhật.')) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 fw-bold mb-0">Khám phá thêm sản phẩm</h2>
            <a href="/product" class="btn btn-outline-success btn-sm">Xem tất cả</a>
        </div>
        <div class="row g-3">
            <div class="col-6 col-md-4 col-lg-3">
                <a href="/product" class="card h-100 text-decoration-none text-dark">
                    <img src="/uploads/image/yonex_product/racquets/astrox-88-s-pro.png" class="card-img-top p-3" alt="Vợt cầu lông Yonex">
                    <div class="card-body"><h3 class="h6 mb-0">Vợt cầu lông chính hãng</h3></div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="/product?category=6" class="card h-100 text-decoration-none text-dark">
                    <img src="/uploads/image/yonex_product/footwear/power-cushion-65-junior.png" class="card-img-top p-3" alt="Giày cầu lông">
                    <div class="card-body"><h3 class="h6 mb-0">Giày cầu lông</h3></div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="/product?category=4" class="card h-100 text-decoration-none text-dark">
                    <img src="/uploads/image/yonex_product/shuttlecocks/mavis-200b.png" class="card-img-top p-3" alt="Quả cầu lông">
                    <div class="card-body"><h3 class="h6 mb-0">Quả cầu lông</h3></div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="/product?category=8" class="card h-100 text-decoration-none text-dark">
                    <img src="/uploads/image/yonex_product/accessories/dry-super-grap-3-wraps.png" class="card-img-top p-3" alt="Phụ kiện cầu lông">
                    <div class="card-body"><h3 class="h6 mb-0">Phụ kiện cầu lông</h3></div>
                </a>
            </div>
        </div>
    </section>
</main>

<script>
(() => {
    const quantityInput = document.getElementById('product-quantity');
    const stock = <?= (int) ($product['stock'] ?? 0) ?>;
    const variantInput = document.getElementById('product-variant');
    const product = <?= json_encode([
        'product_id' => (int) $product['id'],
        'purchase_id' => (int) ($product['purchase_id'] ?? 0),
        'name' => $product['name'],
        'image' => $product['thumbnail'] ?: '/assets/image/no-image.svg',
        'price' => $displayPrice,
        'stock' => (int) ($product['stock'] ?? 0),
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

    document.querySelectorAll('[data-product-image]').forEach((button) => {
        button.addEventListener('click', () => {
            document.getElementById('product-image').src = button.dataset.productImage;
        });
    });

    document.getElementById('quantity-decrease').addEventListener('click', () => {
        quantityInput.value = Math.max(1, Number(quantityInput.value || 1) - 1);
    });

    document.getElementById('quantity-increase').addEventListener('click', () => {
        quantityInput.value = Math.min(stock, Number(quantityInput.value || 1) + 1);
    });

    quantityInput.addEventListener('change', () => {
        quantityInput.value = Math.min(stock, Math.max(1, Number(quantityInput.value || 1)));
    });

    document.getElementById('add-product-to-cart').addEventListener('click', () => {
        let cart = [];
        try {
            const storedCart = JSON.parse(localStorage.getItem('cart') || '[]');
            cart = Array.isArray(storedCart) ? storedCart : [];
        } catch (error) {
            console.error('Không thể đọc giỏ hàng đã lưu.', error);
        }

        const quantity = Math.min(stock, Math.max(1, Number(quantityInput.value || 1)));
        if (variantInput) product.variant = variantInput.value;
        const existing = cart.find((item) =>
            Number(item.product_id) === product.product_id &&
            (item.variant || '') === (product.variant || '')
        );
        if (existing) {
            existing.quantity = Math.min(stock, Number(existing.quantity || 0) + quantity);
        } else {
            cart.push({ ...product, quantity });
        }

        try {
            localStorage.setItem('cart', JSON.stringify(cart));
            window.location.href = '/cart';
        } catch (error) {
            console.error('Không thể lưu sản phẩm vào giỏ hàng.', error);
            window.alert('Không thể thêm sản phẩm vào giỏ hàng. Vui lòng thử lại.');
        }
    });
})();
</script>
