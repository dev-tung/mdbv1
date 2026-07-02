<div class="container-fluid py-4 mt-5">

    <h3 class="mb-4">Đơn hàng</h3>

    <form id="order-create-form" novalidate>

        <div class="row g-3">

            <!-- CUSTOMER -->
            <div class="col-md-6 position-relative">
                <label class="form-label">Khách hàng</label>

                <input type="text"
                       id="customer_search"
                       class="form-control"
                       placeholder="Tìm khách hàng..."
                       autocomplete="off">

                <input type="hidden" id="customer_id">

                <div id="customer_suggestions"
                     class="list-group position-absolute w-100 d-none z-1"></div>
            </div>

            <!-- DESCRIPTION -->
            <div class="col-md-6">
                <label class="form-label">Mô tả</label>

                <input type="text"
                       id="description"
                       class="form-control"
                       placeholder="Nhập mô tả đơn hàng">
            </div>

            <!-- STATUS -->
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>

                <select id="status" class="form-select">
                    <?php foreach (config('shop.option.order_status') as $key => $status): ?>
                        <option value="<?= $key ?>">
                            <?= $status['label'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- PAYMENT -->
            <div class="col-md-4">
                <label class="form-label">Thanh toán</label>

                <select id="payment" class="form-select">
                    <?php foreach (config('shop.option.payment') as $key => $payment): ?>
                        <option value="<?= $key ?>">
                            <?= $payment['label'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- PAID AMOUNT (NEW) -->
            <div class="col-md-4 d-none" id="paid_amount_wrapper">
                <label class="form-label">Đã thanh toán</label>

                <input type="number"
                       id="paid_amount"
                       class="form-control"
                       min="0"
                       value="0">
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
                     class="list-group position-absolute w-100 d-none"></div>
            </div>

            <!-- PRODUCT TABLE -->
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
                        <div class="mb-0 d-flex flex-wrap gap-5">

                            <span class="fs-5">
                                Tổng tiền
                                <b id="total_amount">0</b> ₫
                            </span>

                            <span class="fs-5">
                                Đã trả
                                <b id="paid_amount_view">0</b> ₫
                            </span>

                            <span class="fs-5">
                                Còn nợ
                                <b id="debt_amount_view">0</b> ₫
                            </span>

                        </div>
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

<script src="<?= asset('js/modules/shop/orders/create.js') ?>"></script>