<div class="container-fluid py-4 mt-5">

    <h3 class="mb-4">
        Cập nhật nhập hàng
    </h3>

    <form id="purchase-update-form" novalidate>

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

                <select id="warehouse_id" class="form-select"></select>

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

import { Supplier } from '/assets/js/modules/shop/purchases/supplier.js';
import { Product } from '/assets/js/modules/shop/purchases/product.js';
import { Warehouse } from '/assets/js/modules/shop/purchases/warehouse.js';
import { Submit } from '/assets/js/modules/shop/purchases/submit.js';
import { Api } from '/assets/js/common/api.js';

document.addEventListener('DOMContentLoaded', async () => {

    const purchaseId = window.location.pathname
        .split('/')
        .filter(Boolean)
        .pop();

    // =========================
    // INIT MODULES
    // =========================
    await Promise.all([
        Supplier.init('/api/suppliers'),
        Product.init('/api/products'),
        Warehouse.init('/api/warehouses')
    ]);

    // =========================
    // FETCH DATA
    // =========================
    const json = await Api.get(`/api/purchases/show/${purchaseId}`);
    if (!json?.success) return;

    const p = json.data;

    // =========================
    // SUPPLIER
    // =========================
    document.getElementById('supplier_id').value = p.supplier_id ?? '';
    document.getElementById('supplier_search').value = p.supplier?.name ?? '';

    // =========================
    // BASIC INFO
    // =========================
    document.getElementById('description').value = p.description ?? '';
    document.getElementById('status').value = p.status ?? 'draft';
    document.getElementById('payment').value = p.payment ?? 'unpaid';

    // =========================
    // WAREHOUSE
    // =========================
    const warehouseEl = document.getElementById('warehouse_id');
    warehouseEl.value = p.warehouse_id ?? '';
    warehouseEl.dispatchEvent(new Event('change'));

    // =========================
    // PRODUCTS
    // =========================
    const products = (p.products ?? []).map(item => ({
        product_id: item.product_id,
        name: item.name,
        price: Number(item.price) || 0,
        quantity: Number(item.quantity) || 1,
        subtotal: Number(item.subtotal) || (Number(item.price) * Number(item.quantity))
    }));

    Product.setItems(products);

    // =========================
    // TOTAL COST (optional but recommended)
    // =========================
    const totalEl = document.getElementById('total_amount');
    if (totalEl) {
        totalEl.innerText = p.total_cost ?? 0;
    }

    // =========================
    // SUBMIT
    // =========================
    document
        .getElementById('purchase-update-form')
        .addEventListener('submit', async (e) => {

            e.preventDefault();

            await Submit.update(`/api/purchases/update/${purchaseId}`, {
                id: p.id
            });
        });

});

</script>