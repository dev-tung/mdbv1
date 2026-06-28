<?php
$statuses = config('shop.option.purchase_status');
$payments = config('shop.option.payment');
?>


<div class="container-fluid py-4 mt-5">

    <h3 class="mb-4">
        Nhập hàng
    </h3>

    <form id="purchase-create-form" novalidate>

        <div class="row g-3">

            <!-- SUPPLIER -->
            <div class="col-md-6 position-relative">

                <label class="form-label">Nhà cung cấp</label>

                <input type="text"
                       id="supplier_search"
                       class="form-control"
                       placeholder="Tìm nhà cung cấp..."
                       autocomplete="off">

                <input type="hidden" id="supplier_id">

                <div id="supplier_suggestions"
                     class="list-group position-absolute w-100 d-none z-1">
                </div>

            </div>

            <!-- DESCRIPTION -->
            <div class="col-md-6">

                <label class="form-label">Mô tả</label>

                <input type="text"
                       id="description"
                       class="form-control"
                       placeholder="Nhập mô tả phiếu nhập">

            </div>

            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>

                <select id="status" class="form-select">
                    <?php foreach ($statuses as $key => $status): ?>
                        <option value="<?= $key ?>">
                            <?= $status['label'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- PAYMENT STATUS -->
            <div class="col-md-3">
                <label class="form-label">Thanh toán</label>

                <select id="payment" class="form-select">
                    <?php foreach ($payments as $key => $payment): ?>
                        <option value="<?= $key ?>">
                            <?= $payment['label'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- WAREHOUSE -->
            <div class="col-md-6">

                <label class="form-label">Kho nhập</label>

                <select id="warehouse_id" class="form-select">
                </select>

            </div>

            <!-- PRODUCT SEARCH -->
            <div class="col-12 position-relative mt-4">

                <label class="form-label">Sản phẩm</label>

                <input type="text"
                       id="product_search"
                       class="form-control"
                       placeholder="Tìm sản phẩm..."
                       autocomplete="off">

                <div id="product_suggestions"
                     class="list-group position-absolute w-100 d-none">
                </div>

            </div>

            <!-- TABLE -->
            <div class="col-12">

                <div class="border rounded p-3">

                    <div class="table-responsive">

                        <table class="table table-sm align-middle mb-0">

                            <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th>SL nhập</th>
                                    <th>Giá nhập</th>
                                    <th>Thành tiền</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>

                            <tbody id="selected_products"></tbody>

                        </table>

                    </div>

                    <div class="mt-3">
                        <h5>
                            Tổng tiền:
                            <span id="total_amount">0</span> ₫
                        </h5>
                    </div>

                </div>

            </div>

            <!-- SUBMIT -->
            <div class="col-12">

                <button type="submit"
                        class="btn btn-outline-secondary mt-3">
                    Tạo phiếu nhập
                </button>

            </div>

        </div>

    </form>
</div>

<script type="module" src="<?= asset('/js/modules/shop/purchases/create.js') ?>"></script>