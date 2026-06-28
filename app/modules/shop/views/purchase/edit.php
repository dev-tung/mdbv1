<?php
$statuses = config('shop.option.purchase_status') ?? [];
$payments = config('shop.option.payment') ?? [];
?>

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
                    Cập nhật phiếu nhập
                </button>

            </div>

        </div>

    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // =========================
    // CONFIG
    // =========================
    const PURCHASE_ID = "<?= $id ?? '' ?>";

    if (!PURCHASE_ID) {
        alert("Thiếu PURCHASE_ID");
        return;
    }

    const API = {
        suppliers: "/api/suppliers",
        products: "/api/products",
        warehouses: "/api/warehouses",

        purchaseShow: "/api/purchases/show/" + PURCHASE_ID,
        purchaseUpdate: "/api/purchases/update"
    };

    let selectedProducts = {};

    const supplierInput = document.getElementById("supplier_search");
    const supplierId = document.getElementById("supplier_id");
    const supplierBox = document.getElementById("supplier_suggestions");

    const productInput = document.getElementById("product_search");
    const productBox = document.getElementById("product_suggestions");

    const warehouseSelect = document.getElementById("warehouse_id");

    const tbody = document.getElementById("selected_products");
    const totalEl = document.getElementById("total_amount");

    function money(v) {
        return Number(v).toLocaleString("vi-VN");
    }

    // =========================
    // LOAD WAREHOUSES
    // =========================
    async function loadWarehouses() {

        const res = await fetch(API.warehouses);
        const json = await res.json();

        const data = json.data || [];

        warehouseSelect.innerHTML = "";

        data.forEach(w => {
            const opt = document.createElement("option");
            opt.value = w.id;
            opt.textContent = w.name;
            warehouseSelect.appendChild(opt);
        });
    }

    // =========================
    // LOAD PURCHASE
    // =========================
    async function loadPurchase() {

        const res = await fetch(API.purchaseShow);
        const json = await res.json();

        const data = json.data;

        supplierInput.value = data.supplier?.name || "";
        supplierId.value = data.supplier_id || "";

        document.getElementById("description").value = data.description || "";
        document.getElementById("status").value = data.status || "";
        document.getElementById("payment").value = data.payment || "";
        warehouseSelect.value = data.warehouse_id || "";

        selectedProducts = {};

        (data.products || []).forEach(p => {
            selectedProducts[p.product_id] = {
                id: p.product_id,
                name: p.name,
                price: Number(p.price),
                quantity: Number(p.quantity)
            };
        });

        render();
    }

    // =========================
    // SUPPLIER SEARCH
    // =========================
    supplierInput.addEventListener("input", async function () {

        const keyword = this.value.trim();

        supplierBox.innerHTML = "";
        supplierId.value = "";

        if (!keyword) {
            supplierBox.classList.add("d-none");
            return;
        }

        const res = await fetch(`${API.suppliers}?keyword=${encodeURIComponent(keyword)}`);
        const json = await res.json();

        const data = json.data || [];

        if (!data.length) {
            supplierBox.classList.add("d-none");
            return;
        }

        data.forEach(s => {

            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "list-group-item list-group-item-action";
            btn.textContent = s.name;

            btn.onclick = () => {
                supplierInput.value = s.name;
                supplierId.value = s.id;
                supplierBox.classList.add("d-none");
            };

            supplierBox.appendChild(btn);
        });

        supplierBox.classList.remove("d-none");
    });

    // =========================
    // PRODUCT SEARCH
    // =========================
    productInput.addEventListener("input", async function () {

        const keyword = this.value.trim();

        productBox.innerHTML = "";

        if (!keyword) {
            productBox.classList.add("d-none");
            return;
        }

        const res = await fetch(`${API.products}?keyword=${encodeURIComponent(keyword)}`);
        const json = await res.json();

        const data = json.data || [];

        if (!data.length) {
            productBox.classList.add("d-none");
            return;
        }

        data.forEach(p => {

            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "list-group-item list-group-item-action";
            btn.textContent = p.name;

            btn.onclick = () => {
                addProduct(p);
                productInput.value = "";
                productBox.classList.add("d-none");
            };

            productBox.appendChild(btn);
        });

        productBox.classList.remove("d-none");
    });

    // =========================
    // ADD PRODUCT
    // =========================
    function addProduct(p) {

        if (selectedProducts[p.id]) return;

        selectedProducts[p.id] = {
            id: p.id,
            name: p.name,
            price: 0,
            quantity: 1
        };

        render();
    }

    // =========================
    // RENDER TABLE
    // =========================
    function render() {

        tbody.innerHTML = "";

        Object.values(selectedProducts).forEach(p => {

            const tr = document.createElement("tr");

            tr.setAttribute("data-id", p.id);

            tr.innerHTML = `
                <td>${p.name}</td>

                <td>
                    <input type="number"
                           min="1"
                           value="${p.quantity}"
                           data-id="${p.id}"
                           class="form-control form-control-sm qty">
                </td>

                <td>
                    <input type="number"
                           min="0"
                           value="${p.price}"
                           data-id="${p.id}"
                           class="form-control form-control-sm price">
                </td>

                <td class="item-total" data-id="${p.id}"></td>

                <td>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger remove"
                            data-id="${p.id}">
                        Xóa
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        });

        bind();
        calc();
    }

    // =========================
    // BIND EVENTS
    // =========================
    function bind() {

        document.querySelectorAll(".qty").forEach(i => {
            i.oninput = () => {
                selectedProducts[i.dataset.id].quantity = +i.value || 1;
                calc();
            };
        });

        document.querySelectorAll(".price").forEach(i => {
            i.oninput = () => {
                selectedProducts[i.dataset.id].price = +i.value || 0;
                calc();
            };
        });

        document.querySelectorAll(".remove").forEach(b => {
            b.onclick = () => {
                delete selectedProducts[b.dataset.id];
                render();
            };
        });
    }

    // =========================
    // CALC TOTAL
    // =========================
    function calc() {

        let total = 0;

        Object.values(selectedProducts).forEach(p => {

            const sum = p.price * p.quantity;

            const el = document.querySelector(`.item-total[data-id="${p.id}"]`);
            if (el) el.textContent = money(sum);

            total += sum;
        });

        totalEl.textContent = money(total);
    }

    // =========================
    // SUBMIT UPDATE
    // =========================
    document.getElementById("purchase-create-form")
        .addEventListener("submit", async function (e) {

            e.preventDefault();

            const supplier = supplierId.value.trim();
            const products = Object.values(selectedProducts);

            // =========================
            // VALIDATE
            // =========================
            if (!supplier) {
                alert("Vui lòng chọn nhà cung cấp");
                return;
            }

            if (!products.length) {
                alert("Vui lòng thêm ít nhất 1 sản phẩm");
                return;
            }

            const payload = {
                id: PURCHASE_ID,
                supplier_id: supplierId.value,
                warehouse_id: warehouseSelect.value,
                description: document.getElementById("description").value,
                status: document.getElementById("status").value,
                payment: document.getElementById("payment").value,
                products: Object.values(selectedProducts).map(p => ({
                    product_id: p.id,
                    quantity: p.quantity,
                    price: p.price
                }))
            };

            const res = await fetch(API.purchaseUpdate, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            });

            const json = await res.json();

            if (!res.ok) {
                alert(json.message || "Lỗi cập nhật phiếu nhập");
                return;
            }

            alert("Cập nhật phiếu nhập thành công");

            window.location.href = "/admin/purchases";
        });

    // =========================
    // INIT
    // =========================
    loadWarehouses();
    loadPurchase();

});
</script>