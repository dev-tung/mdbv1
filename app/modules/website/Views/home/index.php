<main class="container py-4">

    <div id="heroBanner" class="alert alert-dismissible fade show p-0 border-0 mb-5">

        <section
            class="p-5 text-white rounded-3 shadow-sm position-relative"
            style="
                background: linear-gradient(
                    135deg,
                    #2f3e2c 0%,
                    #69a84f 55%,
                    #3f6f32 100%
                );
            ">

            <button
                type="button"
                class="btn-close btn-close-white position-absolute top-0 end-0 shadow-none p-2"
                style="transform: scale(.7);"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

            <div class="container-fluid py-3">

                <h1 class="display-5 fw-bold mb-3">
                    Badminton Shop - Vợt Cầu Lông Chính Hãng
                </h1>

                <p class="col-lg-8 fs-5 opacity-75">
                    Chuyên cung cấp vợt cầu lông, giày cầu lông, túi vợt và phụ kiện chính hãng Yonex, Victor, Lining với giá tốt và bảo hành đầy đủ.
                </p>

                <a
                    href="/product"
                    class="btn btn-light btn-lg fw-semibold"
                    style="color:#2f3e2c;">

                    Mua Ngay

                </a>

            </div>

        </section>

    </div>

    <!-- =========================
        DANH MỤC
    ========================= -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Danh mục sản phẩm</h2>
            <a href="/product" class="text-decoration-none text-success">
                Xem tất cả →
            </a>
        </div>

        <div class="row g-4">

            <?php foreach ($categories[0] as $cat): ?>

                <div class="col-6 col-md-4 col-lg-3">

                    <a href="/product?category=<?= $cat['id'] ?>" class="text-decoration-none text-dark">

                        <div class="card border-0 shadow-sm h-100">

                            <div class="card-body text-center py-5">

                                <h6 class="mb-0 fw-semibold">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </h6>

                            </div>

                        </div>

                    </a>

                </div>

            <?php endforeach; ?>

        </div>
    </section>


    <!-- GIỚI THIỆU SEO -->
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
                Cửa Hàng Cầu Lông Chính Hãng Tại Việt Nam
            </h2>

            <p>
                Badminton Shop chuyên cung cấp các dòng vợt cầu lông chính hãng từ Yonex, Victor, Lining cùng nhiều thương hiệu nổi tiếng khác. Chúng tôi mang đến giải pháp mua sắm cầu lông toàn diện với đầy đủ vợt, giày, túi và phụ kiện.
            </p>

            <p class="mb-0">
                Tất cả sản phẩm đều được kiểm tra kỹ trước khi giao hàng, đảm bảo chất lượng và chính sách bảo hành rõ ràng. Với đội ngũ tư vấn giàu kinh nghiệm, khách hàng sẽ dễ dàng lựa chọn sản phẩm phù hợp với trình độ và phong cách thi đấu.
            </p>

        </section>

    </div>

</main>