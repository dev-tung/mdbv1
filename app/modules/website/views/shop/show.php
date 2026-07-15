<main class="container py-4">

    <?php // normalize image url
// normalize image url
// normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    // normalize image url
    function img_url($img)
    {
    	if (!$img) {
    		return 'https://placehold.co/600x600';
    	}
    	if (str_starts_with($img, 'http')) {
    		return $img;
    	}
    	return '/' . ltrim($img, '/');
    } ?>

    <div class="row g-5">

        <!-- LEFT -->
        <div class="col-lg-6">

            <?php $mainImage = img_url($product['thumbnail'] ?? null); ?>

            <div class="bg-white border rounded p-3 text-center">

                <img id="mainImg"
                     src="<?= htmlspecialchars($mainImage) ?>"
                     class="img-fluid rounded"
                     style="max-height:500px; object-fit:contain;"
                     alt="<?= htmlspecialchars($product['name'] ?? '') ?>">

            </div>

            <!-- GALLERY -->
            <?php if (!empty($product['gallery']) && is_array($product['gallery'])): ?>
                <div class="d-flex gap-2 mt-3 flex-wrap justify-content-center">

                    <?php foreach ($product['gallery'] as $img): ?>
                        <?php $imgUrl = img_url($img); ?>

                        <img src="<?= htmlspecialchars($imgUrl) ?>"
                             class="border rounded"
                             style="width:60px;height:60px;object-fit:cover;cursor:pointer"
                             onclick="document.getElementById('mainImg').src=this.src">

                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-6">

            <!-- CATEGORY -->
            <div class="text-uppercase text-muted small mb-2">
                <?= htmlspecialchars($product['category_name'] ?? '') ?>
            </div>

            <!-- NAME -->
            <h1 class="fw-bold mb-3">
                <?= htmlspecialchars($product['name'] ?? '') ?>
            </h1>

            <!-- DESCRIPTION -->
            <?php if (!empty($product['description'])): ?>
                <p class="text-secondary mb-4">
                    <?= nl2br(htmlspecialchars($product['description'])) ?>
                </p>
            <?php endif; ?>


            <!-- PRICE -->
            <div class="mb-4">

                <div class="fs-3 fw-bold text-success">

                    <?php
                    $price = $product['price'] ?? 0;
                    $sale = $product['sale_price'] ?? 0;
                    ?>

                    <?php if ($price > 0): ?>

                        <?php if ($sale > 0): ?>

                            <small class="text-muted text-decoration-line-through me-2">
                                <?= number_format($price, 0, ',', '.') ?> ₫
                            </small>

                            <span class="fw-bold text-danger">
                                <?= number_format($sale, 0, ',', '.') ?> ₫
                            </span>

                        <?php else: ?>

                            <span class="fw-bold">
                                <?= number_format($price, 0, ',', '.') ?> ₫
                            </span>

                        <?php endif; ?>

                    <?php else: ?>

                        <span>Tạm hết hàng</span>

                    <?php endif; ?>

                </div>


                <?php if (($product['stock'] ?? 0) > 0): ?>

                    <button
                        class="btn btn-success mt-3"
                        data-id="<?= $product['id'] ?>"
                        data-name="<?= urlencode($product['name']) ?>"
                        data-price="<?= $sale > 0 ? $sale : $price ?>"
                        data-image="<?= urlencode($product['thumbnail'] ?? '') ?>"
                        data-stock="<?= $product['stock'] ?>"
                        onclick="handleBuy(this)"
                    >
                        Mua hàng
                    </button>

                <?php else: ?>

                    <a href="https://zalo.me/0973359165"
                    class="btn btn-outline-secondary mt-3">
                        Liên hệ đặt hàng
                    </a>

                <?php endif; ?>

            </div>

            <!-- ATTRIBUTES -->
            <?php if (!empty($product['attributes']) && is_array($product['attributes'])): ?>
                <div class="border rounded p-3 bg-light">

                    <div class="fw-bold mb-3">Đặc điểm chi tiết</div>

                    <table class="table table-sm mb-0">

                        <?php foreach ($product['attributes'] as $attr): ?>
                            <tr>
                                <th class="text-muted fw-normal">
                                    <?= htmlspecialchars($attr['attribute_name']) ?>
                                </th>
                                <td class="fw-semibold">
                                    <?= htmlspecialchars($attr['attribute_value']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </table>

                </div>
            <?php endif; ?>

        </div>

    </div>

</main>
<script>
function buyNow(id, name, price, image, stock) {

    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    const index = cart.findIndex(item => item.product_id === id);

    if (index !== -1) {

        if (cart[index].quantity + 1 > stock) {
            alert(`Chỉ còn ${stock} sản phẩm trong kho!`);
            return;
        }

        cart[index].quantity += 1;

    } else {

        if (stock <= 0) {
            alert('Sản phẩm đã hết hàng!');
            return;
        }

        cart.push({
            product_id: id,
            name,
            price,
            image,
            quantity: 1,
            stock: stock
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));

    window.location.href = '/cart';
}

window.handleBuy = function (el) {

    const id = Number(el.dataset.id);
    const name = decodeURIComponent(el.dataset.name);
    const price = Number(el.dataset.price || 0);
    const image = decodeURIComponent(el.dataset.image);
    const stock = Number(el.dataset.stock || 0);

    buyNow(id, name, price, image, stock);
};
</script>