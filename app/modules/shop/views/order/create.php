<?php
$statuses = config('shop.option.order_status');
$payments = config('shop.option.payment');
?>

<div class="container-fluid py-4 mt-5">

    <h3 class="mb-4">
        Tạo đơn hàng
    </h3>

    <form id="order-create-form" novalidate>

        <div class="row g-3">

            <!-- CUSTOMER -->
            <div class="col-md-4 position-relative">

                <label class="form-label">Khách hàng</label>

                <input type="text"
                       id="customer_search"
                       class="form-control"
                       placeholder="Tìm khách hàng..."
                       autocomplete="off">

                <input type="hidden" id="customer_id">

                <div id="customer_suggestions"
                     class="list-group position-absolute w-100 d-none z-1">
                </div>

            </div>

            <!-- STATUS -->
            <div class="col-md-4">
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
            <div class="col-md-4">
                <label class="form-label">Thanh toán</label>

                <select id="payment" class="form-select">
                    <?php foreach ($payments as $key => $payment): ?>
                        <option value="<?= $key ?>">
                            <?= $payment['label'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- DESCRIPTION -->
            <div class="col-md-12">

                <label class="form-label">Mô tả</label>

                <input type="text"
                       id="description"
                       class="form-control"
                       placeholder="Nhập mô tả đơn hàng">

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
                                    <th>SL</th>
                                    <th>Giá</th>
                                    <th>Giảm giá</th>
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
                    Tạo đơn hàng
                </button>

            </div>

        </div>

    </form>
</div>

<script type="module" src="<?= asset('/js/modules/shop/orders/create.js') ?>"></script>