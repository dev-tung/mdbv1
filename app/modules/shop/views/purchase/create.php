<div class="container-fluid py-4 mt-5">

    <h3 class="mb-4">Nhập hàng</h3>

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
                     class="list-group position-absolute w-100 d-none z-1"></div>
            </div>

            <!-- DESCRIPTION -->
            <div class="col-md-6">
                <label class="form-label">Mô tả</label>

                <input type="text"
                       id="description"
                       class="form-control"
                       placeholder="Nhập mô tả phiếu nhập">
            </div>

            <!-- STATUS -->
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>

                <select id="status" class="form-select">
                    <?php foreach (config('shop.option.purchase_status') as $key => $status): ?>
                        <option value="<?= $key ?>">
                            <?= $status['label'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- WAREHOUSE -->
            <div class="col-md-3">
                <label class="form-label">Kho nhập</label>

                <select id="warehouse_id" class="form-select"></select>
            </div>

            <!-- PAYMENT -->
            <div class="col-md-3">
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
            <div class="col-md-3 d-none" id="paid_amount_wrapper">
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
                        <h5 class="mb-0 d-flex flex-wrap gap-5">

                            <span>
                                Tổng tiền:
                                <b id="total_amount">0</b> ₫
                            </span>

                            <span>
                                Đã trả:
                                <b id="paid_amount_view">0</b> ₫
                            </span>

                            <span>
                                Còn nợ:
                                <b id="debt_amount_view">0</b> ₫
                            </span>

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

<script type="module">

    import { Supplier } from '/assets/js/modules/shop/purchases/supplier.js';
    import { Product } from '/assets/js/modules/shop/purchases/product.js';
    import { Warehouse } from '/assets/js/modules/shop/purchases/warehouse.js';
    import { Payment } from '/assets/js/modules/shop/purchases/payment.js';
    import { Submit } from '/assets/js/modules/shop/purchases/submit.js';

    document.addEventListener('DOMContentLoaded', () => {

        Supplier.init('/api/suppliers');
        Product.init('/api/products');
        Warehouse.init('/api/warehouses');

        // payment module
        Payment.init();

        // submit
        document
            .getElementById('purchase-create-form')
            .addEventListener('submit', async (e) => {

                e.preventDefault();

                await Submit.create('/api/purchases');

            });

    });

</script>