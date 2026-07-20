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

                            <?php foreach ($categories[0] as $cat): ?>
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

                        <?php foreach ($brands[0] as $brand): ?>
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