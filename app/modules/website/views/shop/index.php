<main class="container py-3">

    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">

        <ol class="breadcrumb small mb-0">

            <li class="breadcrumb-item">
                <a href="/" class="text-decoration-none text-secondary">
                    Trang chủ
                </a>
            </li>

            <li class="breadcrumb-item">
                <a href="/product" class="text-decoration-none text-secondary">
                    Sản phẩm
                </a>
            </li>
        </ol>

    </nav>

    <!-- RECENTLY VIEWED -->
    <div id="recentViewed" class="alert alert-dismissible fade show p-0 border-0 my-3">

        <section class="border rounded-3 bg-white shadow-sm position-relative p-3">

            <button
                type="button"
                class="btn-close position-absolute top-0 end-0 shadow-none p-2"
                style="transform: scale(.7);"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

            <div class="fw-semibold mb-3">
                Sản phẩm đã xem
            </div>

            <div class="d-flex flex-wrap gap-2 pe-4">

                <a href="#" class="text-decoration-none text-dark">

                    <div class="d-flex align-items-center border rounded px-2 py-2 bg-light">

                        <img
                            src="https://placehold.co/40x40?text=Y"
                            class="rounded"
                            width="40"
                            height="40"
                            alt="">

                        <div class="ms-2">

                            <div class="small fw-semibold">
                                Yonex Astrox 100ZZ
                            </div>

                            <div class="small text-danger fw-bold">
                                5.990.000₫
                            </div>

                        </div>

                    </div>

                </a>

                <a href="#" class="text-decoration-none text-dark">

                    <div class="d-flex align-items-center border rounded px-2 py-2 bg-light">

                        <img
                            src="https://placehold.co/40x40?text=Y"
                            class="rounded"
                            width="40"
                            height="40"
                            alt="">

                        <div class="ms-2">

                            <div class="small fw-semibold">
                                Astrox 77 Pro
                            </div>

                            <div class="small text-danger fw-bold">
                                4.690.000₫
                            </div>

                        </div>

                    </div>

                </a>

                <a href="#" class="text-decoration-none text-dark">

                    <div class="d-flex align-items-center border rounded px-2 py-2 bg-light">

                        <img
                            src="https://placehold.co/40x40?text=L"
                            class="rounded"
                            width="40"
                            height="40"
                            alt="">

                        <div class="ms-2">

                            <div class="small fw-semibold">
                                Aeronaut 9000C
                            </div>

                            <div class="small text-danger fw-bold">
                                3.890.000₫
                            </div>

                        </div>

                    </div>

                </a>

                <a href="#" class="text-decoration-none text-dark">

                    <div class="d-flex align-items-center border rounded px-2 py-2 bg-light">

                        <img
                            src="https://placehold.co/40x40?text=F"
                            class="rounded"
                            width="40"
                            height="40"
                            alt="">

                        <div class="ms-2">

                            <div class="small fw-semibold">
                                Felet TJ1000
                            </div>

                            <div class="small text-danger fw-bold">
                                2.490.000₫
                            </div>

                        </div>

                    </div>

                </a>

            </div>

        </section>

    </div>

    <!-- PRODUCT LIST -->
    <div class="border rounded bg-white shadow-sm p-3 my-3">

        <!-- FILTER -->
        <div class="row g-3 align-items-end">

            <div class="col-12 col-lg-3">

                <label class="form-label form-label-sm fw-semibold">
                    Từ khóa
                </label>

                <input
                    type="text"
                    id="filter-keyword"
                    class="form-control form-control-sm"
                    placeholder="Tên sản phẩm...">

            </div>

            <div class="col-12 col-md-6 col-lg">

                <label class="form-label form-label-sm fw-semibold">
                    Danh mục
                </label>

                <select
                    id="filter-category"
                    class="form-select form-select-sm">
                </select>

            </div>

            <div class="col-12 col-md-6 col-lg">

                <label class="form-label form-label-sm fw-semibold">
                    Thương hiệu
                </label>

                <select
                    id="filter-brand"
                    class="form-select form-select-sm">
                </select>

            </div>

            <div class="col-12 col-md-6 col-lg">

                <label class="form-label form-label-sm fw-semibold">
                    Khoảng giá
                </label>

                <select
                    id="filter-price"
                    class="form-select form-select-sm">

                    <option value="">Tất cả mức giá</option>
                    <option value="0-500000">Dưới 500.000₫</option>
                    <option value="500000-1000000">500.000₫ - 1.000.000₫</option>
                    <option value="1000000-2000000">1.000.000₫ - 2.000.000₫</option>
                    <option value="2000000">Trên 2.000.000₫</option>

                </select>

            </div>

            <div class="col-12 col-md-6 col-lg">

                <label class="form-label form-label-sm fw-semibold">
                    Tình trạng
                </label>

                <select
                    id="filter-status"
                    class="form-select form-select-sm">

                    <option value="">Tất cả</option>
                    <option value="1">Đang bán</option>
                    <option value="0">Ngừng bán</option>

                </select>

            </div>

        </div>

        <!-- GRID -->
        <section class="mt-4">

            <div
                id="product-grid"
                class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3">
            </div>

        </section>

        <template id="product-card-template">

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <img
                        src=""
                        class="card-img-top product-image"
                        alt="">

                    <div class="card-body d-flex flex-column">

                        <small class="text-secondary product-brand"></small>

                        <h6 class="card-title mt-1 mb-2 product-name"></h6>

                        <div class="fw-bold text-danger mb-3 product-price"></div>

                        <a
                            href="#"
                            class="btn btn-outline-primary btn-sm mt-auto product-link">
                            Xem chi tiết
                        </a>

                    </div>

                </div>

            </div>

        </template>

        <!-- PAGINATION -->
        <nav
            class="mt-4"
            aria-label="Phân trang sản phẩm">

            <ul
                id="pagination"
                class="pagination pagination-sm justify-content-center mb-0">
            </ul>

        </nav>

    </div>

    <!-- GIỚI THIỆU SHOP -->
    <div class="alert alert-dismissible fade show p-0 border-0 mb-0">

        <section class="bg-light border rounded-3 p-5 position-relative">

            <button
                type="button"
                class="btn-close btn-sm position-absolute top-0 end-0 shadow-none p-2"
                style="transform: scale(.7);"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

            <h2 class="fw-bold mb-4">
                Shop Cầu Lông Chính Hãng – Vợt, Giày & Phụ Kiện Chất Lượng
            </h2>

            <p>
                Chào mừng bạn đến với <strong>Manh Dung Badminton Shop</strong> – nơi chuyên cung cấp các sản phẩm cầu lông chính hãng từ những thương hiệu hàng đầu như <strong>Yonex, Lining, Victor, Mizuno, Kumpoo, Felet</strong> và nhiều thương hiệu uy tín khác. Tại đây, bạn có thể dễ dàng tìm thấy vợt cầu lông, giày, quần áo, túi đựng, dây cước, quấn cán và đầy đủ phụ kiện đáp ứng mọi nhu cầu tập luyện và thi đấu.
            </p>

            <p class="mb-0">
                Chúng tôi cam kết mang đến sản phẩm chính hãng 100%, giá bán cạnh tranh, tư vấn tận tâm cùng chính sách bảo hành minh bạch. Dù bạn là người mới bắt đầu hay vận động viên chuyên nghiệp, Badminton Shop luôn sẵn sàng đồng hành để giúp bạn lựa chọn thiết bị phù hợp và nâng cao trải nghiệm trên sân cầu.
            </p>

        </section>

    </div>

</main>