<main class="container py-4">

    <div class="row g-4">

        <!-- FILTER -->
        <aside class="col-12 col-lg-3">

            <div class="position-sticky" style="top:20px;">

                <div class="border rounded bg-white shadow-sm p-3">

                    <h5 class="fw-bold mb-3 text-default">
                        Bộ lọc
                    </h5>

                    <!-- CATEGORY -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Danh mục
                        </label>

                        <select class="form-select form-select-sm"
                                id="filter-category">

                            <option value="">
                                Tất cả danh mục
                            </option>

                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>">
                                    <?= $cat['name'] ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <hr class="my-3">

                    <!-- BRAND -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Thương hiệu
                        </label>

                        <?php foreach ($brands as $brand): ?>
                            <div class="form-check small mb-1">

                                <input class="form-check-input"
                                       type="checkbox"
                                       name="brand[]"
                                       value="<?= $brand['id'] ?>">

                                <label class="form-check-label">
                                    <?= $brand['name'] ?>
                                </label>

                            </div>
                        <?php endforeach; ?>

                    </div>

                    <hr class="my-3">

                    <!-- PRICE -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Khoảng giá
                        </label>

                        <?php $priceRanges = config('shop.option.price_range') ?? []; ?>

                        <?php foreach ($priceRanges as $key => $item): ?>

                        <div class="form-check small mb-1">

                            <input class="form-check-input"
                                   type="radio"
                                   name="price"
                                   id="price_<?= $key ?>"
                                   value="<?= $key ?>">

                            <label class="form-check-label"
                                   for="price_<?= $key ?>">
                                <?= $item['label'] ?>
                            </label>

                        </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        </aside>

        <!-- PRODUCTS -->
        <section class="col-12 col-lg-9">

            <div class="row g-3" id="product-list">

                <div class="col-12 text-center">
                    Đang tải...
                </div>

            </div>

            <!-- PAGINATION -->
            <nav class="mt-3 d-flex">
                <ul class="pagination pagination-sm shadow-sm mb-0"
                    id="pagination"></ul>
            </nav>

        </section>

    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', () => {

    let currentPage = 1;
    let lastPage = 1;
    let prevPage = 1;
    let nextPage = 1;

    function getKeywordFromUrl() {
        const params = new URLSearchParams(window.location.search);
        return params.get('keyword') || '';
    }

    // =========================
    // ADD TO CART + STOCK LIMIT
    // =========================
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

    // =========================
    // HANDLE BUY (SAFE)
    // =========================
    window.handleBuy = function (el) {

        const id = Number(el.dataset.id);
        const name = decodeURIComponent(el.dataset.name);
        const price = Number(el.dataset.price || 0);
        const image = decodeURIComponent(el.dataset.image);
        const stock = Number(el.dataset.stock || 0);

        buyNow(id, name, price, image, stock);
    };

    async function loadProducts(page = 1) {

        try {

            currentPage = page;

            const keyword = getKeywordFromUrl();

            const category =
                document.getElementById('filter-category')?.value || '';

            const brands = [
                ...document.querySelectorAll('input[name="brand[]"]:checked')
            ].map(item => item.value);

            const priceFilter =
                document.querySelector('input[name="price"]:checked')?.value || '';

            const query = new URLSearchParams();

            query.append('page', page);

            if (keyword) query.append('keyword', keyword);
            if (category) query.append('category_id', category);
            if (priceFilter) query.append('price', priceFilter);

            brands.forEach(id => query.append('brand[]', id));

            const response = await fetch(`/api/products/stock?${query.toString()}`);
            const json = await response.json();

            const container = document.getElementById('product-list');
            if (!container) return;

            container.innerHTML = '';

            if (!json.data || json.data.length === 0) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-light border text-center">
                            Không có sản phẩm nào
                        </div>
                    </div>
                `;
                return;
            }

            json.data.forEach(product => {

                const image =
                    product.thumbnail ||
                    product.image ||
                    product.featured_image ||
                    '/assets/image/no-image.svg';

                const url =
                    product.slug
                        ? `/product/${product.slug}`
                        : `/product/${product.id}`;

                const price = Number(product.price || 0);
                const salePrice = Number(product.sale_price || 0);
                const stock = Number(product.stock || 0);

                container.innerHTML += `
                    <div class="col-6 col-md-4 col-xl-3">

                        <div class="card h-100 border-0 shadow-sm d-flex flex-column">

                            <a href="${url}" class="text-decoration-none text-dark">

                                <div class="ratio ratio-1x1 bg-light">

                                    <img src="${image}"
                                        alt="${product.name}"
                                        class="w-100 h-100 p-2"
                                        style="object-fit:contain">

                                </div>

                                <div class="card-body flex-grow-1">

                                    <h6 class="mb-2"
                                        style="
                                            display: -webkit-box;
                                            -webkit-line-clamp: 2;
                                            -webkit-box-orient: vertical;
                                            overflow: hidden;
                                            line-height: 1.3em;
                                            height: 2.6em;
                                        ">
                                        ${product.name}
                                    </h6>

                                    <div class="text-danger">

                                        ${
                                            price > 0
                                            ? (
                                                salePrice > 0
                                                ? `
                                                    <small class="text-muted text-decoration-line-through me-1">
                                                        ${price.toLocaleString('vi-VN')} ₫
                                                    </small>
                                                    <span class="fw-bold">
                                                        ${salePrice.toLocaleString('vi-VN')} ₫
                                                    </span>
                                                `
                                                : `
                                                    <span class="fw-bold">
                                                        ${price.toLocaleString('vi-VN')} ₫
                                                    </span>
                                                `
                                            )
                                            : `<span>Tạm hết hàng</span>`
                                        }

                                    </div>

                                </div>

                            </a>

                            <div class="p-2 pt-0">

                                ${
                                    stock > 0
                                    ? `
                                        <button
                                            class="btn btn-outline-success btn-sm w-100"
                                            data-id="${product.id}"
                                            data-name="${encodeURIComponent(product.name)}"
                                            data-price="${salePrice > 0 ? salePrice : price}"
                                            data-image="${encodeURIComponent(image)}"
                                            data-stock="${stock}"
                                            onclick="handleBuy(this)"
                                        >
                                            Mua hàng
                                        </button>
                                    `
                                    : `
                                        <a href="https://zalo.me/0973359165"
                                           class="btn btn-outline-secondary btn-sm w-100">
                                            Liên hệ đặt hàng
                                        </a>
                                    `
                                }

                            </div>

                        </div>

                    </div>
                `;
            });

            lastPage = json.meta?.totalPages || 1;
            prevPage = Math.max(1, page - 1);
            nextPage = Math.min(lastPage, page + 1);

            renderPagination(page, lastPage);

        } catch (e) {

            console.error(e);

            document.getElementById('product-list').innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        Lỗi tải dữ liệu sản phẩm
                    </div>
                </div>
            `;
        }
    }

    function renderPagination(page, totalPages) {

        const pagination = document.getElementById('pagination');
        if (!pagination) return;

        let html = '';

        html += `
            <li class="page-item ${page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="1">Đầu</a>
            </li>

            <li class="page-item ${page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${prevPage}">‹</a>
            </li>
        `;

        for (let i = 1; i <= totalPages; i++) {

            if (i === 1 || i === totalPages || (i >= page - 2 && i <= page + 2)) {

                html += `
                    <li class="page-item ${i === page ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">
                            ${i}
                        </a>
                    </li>
                `;
            }
        }

        html += `
            <li class="page-item ${page === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${nextPage}">›</a>
            </li>

            <li class="page-item ${page === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${lastPage}">Cuối</a>
            </li>
        `;

        pagination.innerHTML = html;
    }

    document.addEventListener('change', e => {
        if (
            e.target.id === 'filter-category' ||
            e.target.name === 'brand[]' ||
            e.target.name === 'price'
        ) {
            loadProducts(1);
        }
    });

    document.addEventListener('click', e => {

        const link = e.target.closest('[data-page]');
        if (!link) return;

        e.preventDefault();

        const page = parseInt(link.dataset.page);
        if (!isNaN(page)) loadProducts(page);
    });

    loadProducts(1);
});
</script>