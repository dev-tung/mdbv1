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
    <!-- <div id="recentViewed" class="alert alert-dismissible fade show p-0 border-0 my-3">

        <section class="border rounded-3 bg-white shadow-sm position-relative p-3">

            <button
                type="button"
                class="btn-close position-absolute top-0 end-0 shadow-none p-2"
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

    </div> -->

    <!-- PRODUCT LIST -->
    <div class="border rounded bg-white shadow-sm p-3 my-3">
        <div class="mb-3">
            <h1 class="h3 fw-bold mb-1">Sản phẩm cầu lông</h1>
            <p class="text-secondary mb-0">Chọn sản phẩm chính hãng, kiểm tra tồn kho và thêm nhanh vào giỏ hàng.</p>
        </div>

        <!-- FILTER -->
        <form method="get" action="/product" class="row g-3 align-items-end">
            <input type="hidden" name="keyword" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>">

            <!-- CATEGORY -->
            <div class="col-12 col-md-6 col-lg">

                <label for="filter-category" class="form-label form-label-sm fw-semibold">
                    Danh mục
                </label>

                <select id="filter-category" name="category_id" class="form-select form-select-sm">
                    <option value="">Tất cả danh mục</option>
                    <option value="1" <?= ($filters['category_id'] ?? null) === 1 ? 'selected' : '' ?>>Vợt cầu lông</option>
                    <option value="2" <?= ($filters['category_id'] ?? null) === 2 ? 'selected' : '' ?>>Cước cầu lông</option>
                    <option value="3" <?= ($filters['category_id'] ?? null) === 3 ? 'selected' : '' ?>>Máy đan vợt</option>
                    <option value="4" <?= ($filters['category_id'] ?? null) === 4 ? 'selected' : '' ?>>Quả cầu lông</option>
                    <option value="5" <?= ($filters['category_id'] ?? null) === 5 ? 'selected' : '' ?>>Quần áo cầu lông</option>
                    <option value="6" <?= ($filters['category_id'] ?? null) === 6 ? 'selected' : '' ?>>Giày cầu lông</option>
                    <option value="7" <?= ($filters['category_id'] ?? null) === 7 ? 'selected' : '' ?>>Túi cầu lông</option>
                    <option value="8" <?= ($filters['category_id'] ?? null) === 8 ? 'selected' : '' ?>>Phụ kiện cầu lông</option>
                </select>

            </div>


            <!-- BRAND -->
            <div class="col-12 col-md-6 col-lg">

                <label for="filter-brand" class="form-label form-label-sm fw-semibold">
                    Thương hiệu
                </label>

                <select id="filter-brand" name="brand_id" class="form-select form-select-sm">
                    <option value="">Tất cả thương hiệu</option>
                    <option value="1" <?= ($filters['brand_id'] ?? null) === 1 ? 'selected' : '' ?>>Yonex</option>
                    <option value="2" <?= ($filters['brand_id'] ?? null) === 2 ? 'selected' : '' ?>>Lining</option>
                    <option value="3" <?= ($filters['brand_id'] ?? null) === 3 ? 'selected' : '' ?>>Victor</option>
                    <option value="4" <?= ($filters['brand_id'] ?? null) === 4 ? 'selected' : '' ?>>Mizuno</option>
                    <option value="5" <?= ($filters['brand_id'] ?? null) === 5 ? 'selected' : '' ?>>Apavi</option>
                    <option value="6" <?= ($filters['brand_id'] ?? null) === 6 ? 'selected' : '' ?>>Khác</option>
                </select>

            </div>


            <!-- PRICE -->
            <div class="col-12 col-md-6 col-lg">

                <label for="filter-price" class="form-label form-label-sm fw-semibold">
                    Khoảng giá
                </label>

                <select id="filter-price" name="price" class="form-select form-select-sm">

                    <option value="">
                        Tất cả mức giá
                    </option>

                    <option value="0-500000" <?= ($filters['price'] ?? '') === '0-500000' ? 'selected' : '' ?>>
                        Dưới 500.000₫
                    </option>

                    <option value="500000-1000000" <?= ($filters['price'] ?? '') === '500000-1000000' ? 'selected' : '' ?>>
                        500.000₫ - 1.000.000₫
                    </option>

                    <option value="1000000-2000000" <?= ($filters['price'] ?? '') === '1000000-2000000' ? 'selected' : '' ?>>
                        1.000.000₫ - 2.000.000₫
                    </option>

                    <option value="2000000" <?= ($filters['price'] ?? '') === '2000000' ? 'selected' : '' ?>>
                        Trên 2.000.000₫
                    </option>

                </select>

            </div>


            <!-- STATUS -->
            <div class="col-12 col-md-6 col-lg">

                <label for="filter-status" class="form-label form-label-sm fw-semibold">
                    Tình trạng
                </label>

                <select id="filter-status" name="status" class="form-select form-select-sm">

                    <option value="">
                        Tất cả
                    </option>

                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>
                        Đang bán
                    </option>

                    <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                        Ngừng bán
                    </option>

                </select>

            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success btn-sm">Áp dụng bộ lọc</button>
                <a href="/product" class="btn btn-outline-secondary btn-sm">Xóa bộ lọc</a>
            </div>
        </form>


        <!-- GRID -->
        <section class="mt-4">

            <p class="small text-secondary"><?= $totalProducts ?> sản phẩm phù hợp</p>
            <div id="product-grid" class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3">

            <?php if (isset($products)): ?>
                <?php if (!$products): ?>
                    <div class="col-12">
                        <p class="alert alert-light border mb-0">Không tìm thấy sản phẩm phù hợp với tiêu chí đã chọn.</p>
                    </div>
                <?php endif; ?>
                <?php foreach ($products as $product): ?>
                    <?php
                    $productId = (int) ($product['id'] ?? 0);
                    $productName = (string) ($product['name'] ?? '');
                    $regularPrice = (float) ($product['price'] ?? 0);
                    $salePrice = (float) ($product['sale_price'] ?? 0);
                    $hasDiscount = $salePrice > 0 && $salePrice < $regularPrice;
                    $displayPrice = $hasDiscount ? $salePrice : $regularPrice;
                    $stock = max(0, (int) ($product['stock'] ?? 0));
                    $canBuy = ($product['status'] ?? '') === 'active' && $stock > 0;
                    ?>
                    <div class="col">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <img
                                src="<?= htmlspecialchars($product['thumbnail'] ?? '/assets/image/no-image.svg') ?>"
                                class="card-img-top product-image"
                                alt="<?= htmlspecialchars($productName) ?>"
                            >
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title mt-1 mb-2 product-name">
                                    <a href="/product/<?= $productId ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($productName) ?></a>
                                </h6>
                                <div class="fw-bold text-danger mb-3 product-price">
                                    <?php if ($hasDiscount): ?>
                                        <span class="text-decoration-line-through text-secondary me-2"><?= number_format($regularPrice, 0, ',', '.') ?> đ</span>
                                    <?php endif; ?>
                                    <span><?= number_format($displayPrice, 0, ',', '.') ?> đ</span>
                                </div>
                                <div class="mt-auto product-action">
                                    <button
                                        type="button"
                                        class="btn btn-success btn-sm w-100 product-buy-now"
                                        data-product-id="<?= $productId ?>"
                                        data-purchase-id="<?= (int) ($product['purchase_id'] ?? 0) ?>"
                                        data-stock="<?= $canBuy ? $stock : 0 ?>"
                                        data-price="<?= $displayPrice ?>"
                                        <?= $canBuy ? '' : 'disabled' ?>
                                    ><?= $canBuy ? 'Mua ngay' : 'Tạm hết hàng' ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/nanoflare-700-pro.png" class="card-img-top product-image" alt="Vợt cầu lông NANOFLARE 700 PRO">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/58" class="text-dark text-decoration-none">Vợt cầu lông NANOFLARE 700 PRO</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            4.700.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            4.690.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="58" data-purchase-id="116" data-stock="1" data-price="4690000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/arcsaber-7-pro.png" class="card-img-top product-image" alt="Vợt cầu lông ARCSABER 7 PRO">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/57" class="text-dark text-decoration-none">Vợt cầu lông ARCSABER 7 PRO</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">4.600.000 đ</div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="57" data-purchase-id="183" data-stock="1" data-price="4600000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/astrox-77-pro.png" class="card-img-top product-image" alt="Vợt cầu lông ASTROX 77 PRO">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/56" class="text-dark text-decoration-none">Vợt cầu lông ASTROX 77 PRO</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            4.500.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            4.490.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="56" data-purchase-id="156" data-stock="2" data-price="4490000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/nanoflare-700-play.jpg" class="card-img-top product-image" alt="Vợt cầu lông NANOFLARE 700 PLAY">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/55" class="text-dark text-decoration-none">Vợt cầu lông NANOFLARE 700 PLAY</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            1.400.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            1.390.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="55" data-purchase-id="149" data-stock="1" data-price="1390000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/arcsaber-7-play.jpg" class="card-img-top product-image" alt="Vợt cầu lông ARCSABER 7 PLAY">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/52" class="text-dark text-decoration-none">Vợt cầu lông ARCSABER 7 PLAY</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            1.350.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            1.340.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="52" data-purchase-id="118" data-stock="1" data-price="1340000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/arcsaber-7-tour.jpg" class="card-img-top product-image" alt="Vợt cầu lông ARCSABER 7 TOUR">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/51" class="text-dark text-decoration-none">Vợt cầu lông ARCSABER 7 TOUR</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            2.800.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            2.790.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="51" data-purchase-id="95" data-stock="1" data-price="2790000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/astrox-77-play.jpg" class="card-img-top product-image" alt="Vợt cầu lông ASTROX 77 PLAY">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/50" class="text-dark text-decoration-none">Vợt cầu lông ASTROX 77 PLAY</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            1.000.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            999.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="50" data-purchase-id="183" data-stock="1" data-price="999000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/astrox-77-tour.jpg" class="card-img-top product-image" alt="Vợt cầu lông ASTROX 77 TOUR">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/49" class="text-dark text-decoration-none">Vợt cầu lông ASTROX 77 TOUR</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            2.450.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            2.440.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="49" data-purchase-id="191" data-stock="1" data-price="2440000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/nanoflare-800-play.png" class="card-img-top product-image" alt="Vợt cầu lông NANOFLARE 800 PLAY">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/43" class="text-dark text-decoration-none">Vợt cầu lông NANOFLARE 800 PLAY</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            1.250.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            1.240.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="43" data-purchase-id="101" data-stock="2" data-price="1240000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/nanoflare-800-pro.png" class="card-img-top product-image" alt="Vợt cầu lông NANOFLARE 800 PRO">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/40" class="text-dark text-decoration-none">Vợt cầu lông NANOFLARE 800 PRO</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            5.200.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            5.190.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="40" data-purchase-id="189" data-stock="1" data-price="5190000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/nanoflare-1000-play.png" class="card-img-top product-image" alt="Vợt cầu lông NANOFLARE 1000 PLAY">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/39" class="text-dark text-decoration-none">Vợt cầu lông NANOFLARE 1000 PLAY</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            1.400.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            1.390.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="39" data-purchase-id="40" data-stock="1" data-price="1390000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/arcsaber-11-play.png" class="card-img-top product-image" alt="Vợt cầu lông ARCSABER 11 PLAY">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/32" class="text-dark text-decoration-none">Vợt cầu lông ARCSABER 11 PLAY</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            1.450.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            1.440.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="32" data-purchase-id="206" data-stock="1" data-price="1440000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/arcsaber-11-pro.png" class="card-img-top product-image" alt="Vợt cầu lông ARCSABER 11 PRO">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/30" class="text-dark text-decoration-none">Vợt cầu lông ARCSABER 11 PRO</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">5.000.000 đ</div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="30" data-purchase-id="85" data-stock="1" data-price="5000000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/astrox-7-dg.png" class="card-img-top product-image" alt="Vợt cầu lông ASTROX 7 DG">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/25" class="text-dark text-decoration-none">Vợt cầu lông ASTROX 7 DG</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            1.600.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            1.450.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="25" data-purchase-id="58" data-stock="1" data-price="1450000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/astrox-nextage.png" class="card-img-top product-image" alt="Vợt cầu lông ASTROX NEXTAGE">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/18" class="text-dark text-decoration-none">Vợt cầu lông ASTROX NEXTAGE</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            1.750.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            1.740.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="18" data-purchase-id="73" data-stock="1" data-price="1740000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/astrox-88-play.png" class="card-img-top product-image" alt="Vợt cầu lông ASTROX 88 PLAY">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/15" class="text-dark text-decoration-none">Vợt cầu lông ASTROX 88 PLAY</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            1.200.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            1.190.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="15" data-purchase-id="206" data-stock="2" data-price="1190000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/astrox-88-d-tour.png" class="card-img-top product-image" alt="Vợt cầu lông ASTROX 88 D TOUR">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/13" class="text-dark text-decoration-none">Vợt cầu lông ASTROX 88 D TOUR</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            2.500.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            2.490.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="13" data-purchase-id="113" data-stock="2" data-price="2490000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/astrox-88-d-pro.png" class="card-img-top product-image" alt="Vợt cầu lông ASTROX 88 D PRO">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/12" class="text-dark text-decoration-none">Vợt cầu lông ASTROX 88 D PRO</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            5.100.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            5.090.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="12" data-purchase-id="117" data-stock="1" data-price="5090000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/astrox-88-s-game.png" class="card-img-top product-image" alt="Vợt cầu lông ASTROX 88 S GAME">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/11" class="text-dark text-decoration-none">Vợt cầu lông ASTROX 88 S GAME</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            1.800.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            1.790.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="11" data-purchase-id="100" data-stock="1" data-price="1790000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

        

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="/uploads/image/yonex_product/racquets/astrox-88-s-pro.png" class="card-img-top product-image" alt="Vợt cầu lông ASTROX 88 S PRO">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand"></small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name"><a href="/product/9" class="text-dark text-decoration-none">Vợt cầu lông ASTROX 88 S PRO</a></h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        <span class="text-decoration-line-through text-secondary me-2">
                            5.000.000 đ
                        </span>

                        <span class="text-danger fw-bold">
                            4.900.000 đ
                        </span>
                    </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action"><button type="button" class="btn btn-success btn-sm w-100 product-buy-now" data-product-id="9" data-purchase-id="68" data-stock="1" data-price="4900000">Mua ngay</button></div>

                    </div>

                </div>

            </div>

            <?php endif; ?>

        </div>

        </section>


        <!-- PRODUCT CARD TEMPLATE -->
        <template id="product-card-template">

            <div class="col">

                <div class="card product-card h-100 border-0 shadow-sm">

                    <!-- IMAGE -->
                    <img src="" class="card-img-top product-image" alt="">


                    <div class="card-body d-flex flex-column">

                        <!-- BRAND -->
                        <small class="text-secondary product-brand">
                        </small>


                        <!-- NAME -->
                        <h6 class="card-title mt-1 mb-2 product-name">
                        </h6>


                        <!-- PRICE -->
                        <div class="fw-bold text-danger mb-3 product-price">
                        </div>


                        <!-- ACTION -->
                        <div class="mt-auto product-action">
                        </div>

                    </div>

                </div>

            </div>

        </template>


        <?php
        $paginationParams = array_filter([
            'keyword' => $filters['keyword'],
            'category_id' => $filters['category_id'],
            'brand_id' => $filters['brand_id'],
            'price' => $filters['price'],
            'status' => $filters['status'],
        ], static fn ($value): bool => $value !== null && $value !== '');
        $paginationUrl = static fn (int $page): string => '/product?' . http_build_query($paginationParams + ['page' => $page]);

        if ($totalPages <= 7) {
            $pageItems = range(1, $totalPages);
        } else {
            $pageItems = [1];
            if ($currentPage > 4) {
                $pageItems[] = '...';
            }
            $pageItems = array_merge($pageItems, range(max(2, $currentPage - 2), min($totalPages - 1, $currentPage + 2)));
            if ($currentPage < $totalPages - 3) {
                $pageItems[] = '...';
            }
            $pageItems[] = $totalPages;
        }
        ?>
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4" aria-label="Phân trang sản phẩm">
                <ul class="pagination pagination-sm justify-content-center mb-0">
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= htmlspecialchars($paginationUrl(max(1, $currentPage - 1))) ?>">Trước</a>
                    </li>
                    <?php foreach ($pageItems as $pageItem): ?>
                        <?php if ($pageItem === '...'): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php else: ?>
                            <li class="page-item <?= $pageItem === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= htmlspecialchars($paginationUrl((int) $pageItem)) ?>"><?= (int) $pageItem ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= htmlspecialchars($paginationUrl(min($totalPages, $currentPage + 1))) ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    </div>

    <!-- GIỚI THIỆU SHOP -->
    <div class="alert alert-dismissible fade show p-0 border-0 mb-0">

        <section class="bg-light border rounded-3 p-5 position-relative">

            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 shadow-none p-2" data-bs-dismiss="alert" aria-label="Close">
            </button>

            <h2 class="fw-bold mb-4">
                Shop Cầu Lông Chính Hãng – Vợt, Giày &amp; Phụ Kiện Chất Lượng
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

<script>
(() => {
    document.querySelectorAll('.product-buy-now').forEach((button) => {
        const stock = Number(button.dataset.stock || 0);
        const action = button.closest('.product-action');
        if (!action) return;

        const stockLabel = document.createElement('span');
        stockLabel.className = `badge ${stock > 0 ? 'text-bg-success' : 'text-bg-secondary'} mb-2`;
        stockLabel.textContent = stock > 0 ? `Còn ${stock} sản phẩm` : 'Tạm hết hàng';
        action.prepend(stockLabel);

        button.addEventListener('click', () => {
            if (stock < 1) return;
            const card = button.closest('.card');
            const link = card && card.querySelector('.product-name a');
            const image = card && card.querySelector('img');
            const name = link ? link.textContent.trim() : 'Sản phẩm';
            const item = {
                product_id: Number(button.dataset.productId),
                purchase_id: Number(button.dataset.purchaseId),
                name,
                image: image ? image.getAttribute('src') : '/assets/image/no-image.svg',
                price: Number(button.dataset.price || 0),
                stock,
                quantity: 1
            };

            try {
                const storedCart = JSON.parse(localStorage.getItem('cart') || '[]');
                const cart = Array.isArray(storedCart) ? storedCart : [];
                const existing = cart.find((entry) => Number(entry.product_id) === item.product_id);
                if (existing) {
                    existing.quantity = Math.min(stock, Number(existing.quantity || 0) + 1);
                } else {
                    cart.push(item);
                }
                localStorage.setItem('cart', JSON.stringify(cart));
                window.location.href = '/cart';
            } catch (error) {
                console.error('Không thể lưu sản phẩm vào giỏ hàng.', error);
                window.alert('Không thể thêm sản phẩm vào giỏ hàng. Vui lòng thử lại.');
            }
        });
    });
})();
</script>