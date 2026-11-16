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

    <nav class="mt-3" aria-label="Danh mục sản phẩm">
        <div class="d-flex flex-wrap gap-2">
            <a href="/product?category=1" class="btn btn-outline-success btn-sm">Vợt cầu lông</a>
            <a href="/product?category=6" class="btn btn-outline-success btn-sm">Giày cầu lông</a>
            <a href="/product?category=5" class="btn btn-outline-success btn-sm">Quần áo</a>
            <a href="/product?category=4" class="btn btn-outline-success btn-sm">Quả cầu lông</a>
            <a href="/product?category=8" class="btn btn-outline-success btn-sm">Phụ kiện</a>
        </div>
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
        <div class="row g-3 align-items-end">

            <!-- CATEGORY -->
            <div class="col-12 col-md-6 col-lg">

                <label for="filter-category" class="form-label form-label-sm fw-semibold">
                    Danh mục
                </label>

                <select id="filter-category" class="form-select form-select-sm"><option value="">Tất cả danh mục</option><option value="1">Vợt cầu lông</option><option value="2">Cước cầu lông</option><option value="3">Máy đan vợt</option><option value="4">Quả cầu lông</option><option value="5">Quần áo cầu lông</option><option value="6">Giày cầu lông</option><option value="7">Túi cầu lông</option><option value="8">Phụ kiện cầu lông</option></select>

            </div>


            <!-- BRAND -->
            <div class="col-12 col-md-6 col-lg">

                <label for="filter-brand" class="form-label form-label-sm fw-semibold">
                    Thương hiệu
                </label>

                <select id="filter-brand" class="form-select form-select-sm"><option value="">Tất cả thương hiệu</option><option value="1">Yonex</option><option value="2">Lining</option><option value="3">Victor</option><option value="4">Mizuno</option><option value="5">Apavi</option><option value="6">Khác</option></select>

            </div>


            <!-- PRICE -->
            <div class="col-12 col-md-6 col-lg">

                <label for="filter-price" class="form-label form-label-sm fw-semibold">
                    Khoảng giá
                </label>

                <select id="filter-price" class="form-select form-select-sm">

                    <option value="">
                        Tất cả mức giá
                    </option>

                    <option value="0-500000">
                        Dưới 500.000₫
                    </option>

                    <option value="500000-1000000">
                        500.000₫ - 1.000.000₫
                    </option>

                    <option value="1000000-2000000">
                        1.000.000₫ - 2.000.000₫
                    </option>

                    <option value="2000000">
                        Trên 2.000.000₫
                    </option>

                </select>

            </div>


            <!-- STATUS -->
            <div class="col-12 col-md-6 col-lg">

                <label for="filter-status" class="form-label form-label-sm fw-semibold">
                    Tình trạng
                </label>

                <select id="filter-status" class="form-select form-select-sm">

                    <option value="">
                        Tất cả
                    </option>

                    <option value="1">
                        Đang bán
                    </option>

                    <option value="0">
                        Ngừng bán
                    </option>

                </select>

            </div>

        </div>


        <!-- GRID -->
        <section class="mt-4">

            <div id="product-grid" class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3">

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


        <!-- PAGINATION -->
        <nav class="mt-4" aria-label="Phân trang sản phẩm">

            <ul id="pagination" class="pagination pagination-sm justify-content-center mb-0"><li class="page-item disabled"><a href="#" class="page-link">Trước</a></li><li class="page-item active"><a href="#" class="page-link">1</a></li><li class="page-item"><a href="#" class="page-link">2</a></li><li class="page-item"><a href="#" class="page-link">3</a></li><li class="page-item"><a href="#" class="page-link">4</a></li><li class="page-item"><a href="#" class="page-link">5</a></li><li class="page-item disabled"><span class="page-link">...</span></li><li class="page-item"><a href="#" class="page-link">12</a></li><li class="page-item"><a href="#" class="page-link">Sau</a></li></ul>

        </nav>

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