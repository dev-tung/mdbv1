<div class="container-fluid py-4 mt-5">

    <h3 class="mb-4">
        Cập nhật nhập hàng
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
                     class="list-group position-absolute w-100 d-none z-3">
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

            <!-- STATUS -->
            <div class="col-md-3">

                <label class="form-label">Trạng thái</label>

                <select id="status" class="form-select">
                    <?php foreach ((config('shop.option.purchase_status') ?? []) as $key => $status): ?>
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
                    <?php foreach ((config('shop.option.payment') ?? []) as $key => $payment): ?>
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
                    Cập nhật phiếu nhập
                </button>

            </div>

        </div>

    </form>

</div>
<script type="module">

    import { Supplier } from '/assets/js/modules/purchases/supplier.js';
    import { Product } from '/assets/js/modules/purchases/product.js';
    import { Submit } from '/assets/js/modules/purchases/submit.js';
    import { Api } from '/assets/js/helpers/api.js';

    document.addEventListener('DOMContentLoaded', async () => {

        const pathParts = window.location.pathname.split("/");

        const purchaseId = pathParts[pathParts.length - 1];

        // load chi tiết phiếu nhập
        const json = await Api.get(
            `/api/purchases/show?id=${purchaseId}`
        );

        if (json.success) {

            const purchase = json.data;

            // supplier
            document.getElementById('supplier_id').value =
                purchase.supplier_id ?? '';

            document.getElementById('supplier_search').value =
                purchase.supplier_name ?? '';

            // description
            document.getElementById('description').value =
                purchase.description ?? '';

            // status
            document.getElementById('status').value =
                purchase.status ?? '';

            // payment
            document.getElementById('payment').value =
                purchase.payment ?? '';

            // warehouse
            document.getElementById('warehouse_id').value =
                purchase.warehouse_id ?? '';

            // products
            Product.setItems(purchase.items || []);
        }

        // khởi tạo search
        Supplier.init('/api/suppliers/search');

        Product.init('/api/products/search');

        // submit update
        document
            .getElementById('purchase-create-form')
            .addEventListener('submit', async e => {

                e.preventDefault();

                await Submit.update(
                    `/api/purchases/update?id=${purchaseId}`
                );

            });

    });

</script>