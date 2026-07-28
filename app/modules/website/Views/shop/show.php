<main class="container py-4">

    <div class="row g-4">

        <!-- LEFT CARD -->
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm rounded-3 p-3">

                <!-- MAIN IMAGE -->
                <div class="text-center d-flex align-items-center justify-content-center"
                    style="height:500px;">

                    <img id="mainImg"
                        src="<?=
                            !empty($images)
                                ? '/' . htmlspecialchars($images[0]['image'])
                                : (!empty($product['thumbnail'])
                                    ? '/' . htmlspecialchars($product['thumbnail'])
                                    : '/assets/image/no-image.svg')
                        ?>"
                        class="rounded"
                        style="width:100%;height:100%;object-fit:contain;cursor:zoom-in;"
                        data-bs-toggle="modal"
                        data-bs-target="#imageModal">

                </div>

                <!-- GALLERY -->
                <div class="d-flex gap-2 mt-3 justify-content-center">

                    <?php if (!empty($images)): ?>

                        <?php foreach ($images as $image): ?>

                            <img
                                src="/<?= htmlspecialchars($image['image']) ?>"
                                data-image="/<?= htmlspecialchars($image['image']) ?>"
                                class="border rounded gallery-image"
                                style="width:60px;height:60px;object-fit:cover;cursor:pointer"
                                onclick="
                                    document.getElementById('mainImg').src=this.dataset.image;
                                    document.getElementById('zoomImg').src=this.dataset.image;
                                ">

                        <?php endforeach; ?>

                    <?php else: ?>

                        <img
                            src="<?= !empty($product['thumbnail']) ? '/' . htmlspecialchars($product['thumbnail']) : '/assets/image/no-image.svg' ?>"
                            data-image="<?= !empty($product['thumbnail']) ? '/' . htmlspecialchars($product['thumbnail']) : '/assets/image/no-image.svg' ?>"
                            class="border rounded gallery-image"
                            style="width:60px;height:60px;object-fit:cover;cursor:pointer"
                            onclick="
                                document.getElementById('mainImg').src=this.dataset.image;
                                document.getElementById('zoomImg').src=this.dataset.image;
                            ">

                    <?php endif; ?>

                </div>

            </div>

            <div class="card border-0 shadow-sm rounded-3 mt-4">

                <div class="card-body p-0">

                    <!-- CAM KẾT -->
                    <div class="d-flex align-items-center p-3">

                        <div class="me-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:40px;height:40px;">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="22"
                                height="22"
                                fill="#0d6efd"
                                viewBox="0 0 16 16">
                                <path d="M5.072.56C5.58.329 6.18.16 8 0c1.82.16 2.42.329 2.928.56L14 2v4.496c0 3.606-2.12 6.62-6 8.504-3.88-1.884-6-4.898-6-8.504V2L5.072.56zm2.928.562-2.5.982L2.5 3.08v3.416c0 2.992 1.72 5.502 5.5 7.325 3.78-1.823 5.5-4.333 5.5-7.325V3.08l-3-1.976-2.5-.982z"/>
                                <path d="M10.354 5.646a.5.5 0 0 1 0 .708L7.5 9.207 6.146 7.854a.5.5 0 1 1 .708-.708L7.5 7.793l2.146-2.147a.5.5 0 0 1 .708 0z"/>
                            </svg>

                        </div>

                        <div>
                            <div class="fw-semibold">
                                Mạnh Dũng Sports cam kết
                            </div>

                            <small class="text-muted">
                                Sản phẩm chính hãng, đầy đủ tem và nguồn gốc xuất xứ.
                            </small>
                        </div>

                    </div>

                    <hr class="m-0">

                    <!-- BỘ SẢN PHẨM -->
                    <div class="d-flex align-items-center p-3">

                        <div class="me-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:40px;height:40px;">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="22"
                                height="22"
                                fill="#0d6efd"
                                viewBox="0 0 16 16">
                                <path d="M8.186.113a1 1 0 0 0-.372 0L1.846 2.5a.5.5 0 0 0-.346.474v9.052a.5.5 0 0 0 .314.463l6 2.5a.5.5 0 0 0 .372 0l6-2.5a.5.5 0 0 0 .314-.463V2.974a.5.5 0 0 0-.346-.474L8.186.113z"/>
                            </svg>

                        </div>

                        <div>
                            <div class="fw-semibold">
                                Bộ sản phẩm gồm
                            </div>

                            <small class="text-muted">
                                Vợt, túi đựng vợt, tem chính hãng và phiếu bảo hành.
                            </small>
                        </div>

                    </div>

                    <hr class="m-0">

                    <!-- BẢO HÀNH -->
                    <div class="d-flex align-items-center p-3">

                        <div class="me-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:40px;height:40px;">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="22"
                                height="22"
                                fill="#0d6efd"
                                viewBox="0 0 16 16">
                                <path d="M5.072.56C5.58.329 6.18.16 8 0c1.82.16 2.42.329 2.928.56L14 2v4.496c0 3.606-2.12 6.62-6 8.504-3.88-1.884-6-4.898-6-8.504V2L5.072.56z"/>
                            </svg>

                        </div>

                        <div>
                            <div class="fw-semibold">
                                Bảo hành chính hãng
                            </div>

                            <small class="text-muted">
                                Áp dụng theo chính sách bảo hành của Yonex, Lining, Victor và các thương hiệu phân phối chính hãng tại Việt Nam.
                            </small>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT CARD -->
        <div class="col-lg-5">

            <div class="card border-0 shadow-sm rounded-3 p-4">

                <div class="text-uppercase text-muted small mb-2">
                    <?= htmlspecialchars($product['category_name'] ?? '') ?>
                </div>

                <h1 class="fw-bold mb-3">
                    <?= htmlspecialchars($product['name'] ?? '') ?>
                </h1>

                <p class="text-secondary">
                    <?= nl2br(htmlspecialchars($product['description'] ?? '')) ?>
                </p>

                <div class="my-4">

                    <?php if (($product['sale_price'] ?? 0) > 0 || ($product['price'] ?? 0) > 0): ?>

                        <div class="fs-3 fw-bold">
                            <span class="text-danger">
                                <?= number_format(
                                    ($product['sale_price'] > 0 ? $product['sale_price'] : $product['price']),
                                    0,
                                    ',',
                                    '.'
                                ) ?> ₫
                            </span>
                        </div>

                        <?php if (($product['sale_price'] ?? 0) > 0 && $product['sale_price'] < $product['price']): ?>

                            <div class="text-muted text-decoration-line-through">
                                <?= number_format($product['price'], 0, ',', '.') ?> ₫
                            </div>

                        <?php endif; ?>

                    <?php endif; ?>

                    <a class="btn btn-success mt-3 px-4"
                        href="https://zalo.me/+84973359165"
                        target="_blank">
                        Liên hệ tư vấn & đặt hàng
                    </a>

                </div>

                <div class="border rounded p-3 bg-light">

                    <div class="fw-bold mb-3">
                        Đặc điểm chi tiết
                    </div>

                    <table class="table table-sm mb-0">

                        <tr>
                            <th class="text-muted fw-normal px-2">
                                Thương hiệu
                            </th>

                            <td class="fw-semibold px-2">
                                <?= htmlspecialchars($product['brand_name'] ?? '') ?>
                            </td>
                        </tr>

                        <?php foreach ($attributes as $attribute): ?>

                            <tr>

                                <th class="text-muted fw-normal px-2">
                                    <?= htmlspecialchars($attribute['attribute_name']) ?>
                                </th>

                                <td class="fw-semibold px-2">
                                    <?= htmlspecialchars($attribute['attribute_value']) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </table>

                </div>

            </div>

        </div>

    </div>

</main>
<!-- IMAGE MODAL -->
<div class="modal fade" id="imageModal" tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 rounded-3">

            <div class="modal-body bg-white rounded-3 text-center position-relative p-4">

                <button
                    type="button"
                    class="btn-close position-absolute top-0 end-0 m-3"
                    data-bs-dismiss="modal">
                </button>

                <img
                    id="zoomImg"
                    src=""
                    class="img-fluid rounded shadow"
                    style="
                        max-width:100%;
                        max-height:85vh;
                        cursor:grab;
                        user-select:none;
                        transition:transform .1s ease;
                        transform-origin:center center;
                    ">

            </div>

        </div>

    </div>

</div>
<script>
const mainImg = document.getElementById('mainImg');
const zoomImg = document.getElementById('zoomImg');
const imageModal = document.getElementById('imageModal');

let scale = 1;
let translateX = 0;
let translateY = 0;

let isDragging = false;
let startX = 0;
let startY = 0;

function updateTransform() {
    zoomImg.style.transform =
        `translate(${translateX}px, ${translateY}px) scale(${scale})`;
}

// =========================
// OPEN MODAL
// =========================
mainImg.addEventListener('click', () => {

    zoomImg.src = mainImg.src;

    scale = 1;
    translateX = 0;
    translateY = 0;

    updateTransform();
});

// =========================
// ZOOM
// =========================
zoomImg.addEventListener('wheel', (e) => {

    e.preventDefault();

    scale += (e.deltaY < 0 ? 0.15 : -0.15);

    scale = Math.max(1, Math.min(scale, 5));


    // Khi về ảnh gốc => đưa về giữa
    if (scale === 1) {

        translateX = 0;
        translateY = 0;

    }


    updateTransform();

}, { passive: false });

// =========================
// START DRAG
// =========================
zoomImg.addEventListener('mousedown', (e) => {

    if (scale <= 1) return;

    e.preventDefault();

    isDragging = true;

    startX = e.clientX - translateX;
    startY = e.clientY - translateY;

    zoomImg.style.cursor = 'grabbing';

});

// =========================
// DRAGGING
// =========================
document.addEventListener('mousemove', (e) => {

    if (!isDragging) return;

    translateX = e.clientX - startX;
    translateY = e.clientY - startY;

    updateTransform();

});

// =========================
// STOP DRAG
// =========================
document.addEventListener('mouseup', () => {

    if (!isDragging) return;

    isDragging = false;
    zoomImg.style.cursor = scale > 1 ? 'grab' : 'default';

});

// Nếu chuột rời khỏi cửa sổ cũng dừng kéo
window.addEventListener('blur', () => {

    isDragging = false;
    zoomImg.style.cursor = scale > 1 ? 'grab' : 'default';

});

// =========================
// RESET
// =========================
imageModal.addEventListener('hidden.bs.modal', () => {

    scale = 1;
    translateX = 0;
    translateY = 0;

    isDragging = false;

    zoomImg.style.cursor = 'default';

    updateTransform();

});
</script>