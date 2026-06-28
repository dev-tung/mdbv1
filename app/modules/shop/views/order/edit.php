<?php
$statuses = config('shop.option.order_status');
$payments = config('shop.option.payment');
?>

<div class="container-fluid py-4 mt-5">

    <h3 class="mb-4">
        <?= !empty($id) ? 'Sửa đơn hàng' : 'Tạo đơn hàng' ?>
    </h3>

    <form id="order-create-form">

        <div class="row g-3">

            <!-- CUSTOMER -->
            <div class="col-md-4 position-relative">
                <label class="form-label">Khách hàng</label>

                <input type="text"
                       id="customer_search"
                       class="form-control"
                       placeholder="Tìm khách hàng...">

                <input type="hidden" id="customer_id">

                <div id="customer_suggestions"
                     class="list-group position-absolute w-100 d-none z-1"></div>
            </div>

            <!-- STATUS -->
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select id="status" class="form-select">
                    <?php foreach ($statuses as $k => $v): ?>
                        <option value="<?= $k ?>"><?= $v['label'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- PAYMENT -->
            <div class="col-md-4">
                <label class="form-label">Thanh toán</label>
                <select id="payment" class="form-select">
                    <?php foreach ($payments as $k => $v): ?>
                        <option value="<?= $k ?>"><?= $v['label'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- DESCRIPTION -->
            <div class="col-md-12">
                <label class="form-label">Mô tả</label>
                <input type="text" id="description" class="form-control">
            </div>

            <!-- PRODUCT SEARCH -->
            <div class="col-12 position-relative mt-4">
                <label class="form-label">Sản phẩm</label>

                <input type="text"
                       id="product_search"
                       class="form-control"
                       placeholder="Tìm sản phẩm...">

                <div id="product_suggestions"
                     class="list-group position-absolute w-100 d-none"></div>
            </div>

            <!-- TABLE -->
            <div class="col-12">

                <div class="border rounded p-3">

                    <div class="table-responsive">

                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th>SL</th>
                                    <th>Giá</th>
                                    <th>Giảm</th>
                                    <th>Quà tặng</th>
                                    <th>Tạm tính</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody id="selected_products"></tbody>
                        </table>

                    </div>

                    <h5 class="mt-3">
                        Tổng tiền:
                        <span id="total_amount">0</span> ₫
                    </h5>

                </div>

            </div>

            <!-- SUBMIT -->
            <div class="col-12">
                <button class="btn btn-outline-secondary mt-3">
                    Lưu đơn hàng
                </button>
            </div>

        </div>

    </form>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const ORDER_ID = "<?= $id ?? '' ?>";

    const API = {
        customers: "/api/customers",
        products: "/api/products",
        orders: ORDER_ID ? "/api/orders/show/" + ORDER_ID : "/api/orders"
    };

    let selectedProducts = {};
    let CUSTOMERS_MAP = {};

    const customerInput = document.getElementById("customer_search");
    const customerId = document.getElementById("customer_id");
    const customerBox = document.getElementById("customer_suggestions");

    const productInput = document.getElementById("product_search");
    const productBox = document.getElementById("product_suggestions");

    const tbody = document.getElementById("selected_products");
    const totalEl = document.getElementById("total_amount");

    function money(v) {
        return Number(v || 0).toLocaleString("vi-VN");
    }

    // =========================
    // CUSTOMERS CACHE
    // =========================
    async function loadCustomersCache() {
        const res = await fetch(API.customers);
        const json = await res.json();

        CUSTOMERS_MAP = {};
        (json.data || []).forEach(c => {
            CUSTOMERS_MAP[c.id] = c.name;
        });
    }

    // =========================
    // LOAD ORDER
    // =========================
    async function loadOrder() {
        if (!ORDER_ID) return;

        const res = await fetch(API.orders);
        const json = await res.json();
        const data = json.data;

        customerInput.value = CUSTOMERS_MAP[data.customer_id] || "";
        customerId.value = data.customer_id;

        document.getElementById("description").value = data.description || "";
        document.getElementById("status").value = data.status || "";
        document.getElementById("payment").value = data.payment || "";

        selectedProducts = {};

        (data.products || []).forEach(p => {
            selectedProducts[p.product_id] = {
                id: p.product_id,
                name: p.product_name || `SP #${p.product_id}`,
                quantity: Number(p.quantity || 1),
                price: Number(p.price || 0),
                base_price: Number(p.price || 0),
                discount: Number(p.discount || 0),
                purchase_item_id: p.purchase_item_id,
                gift: Number(p.price) === 0
            };
        });

        render();
    }

    // =========================
    // PRODUCT SEARCH
    // =========================
    productInput.addEventListener("input", async function () {

        const keyword = this.value.trim();
        productBox.innerHTML = "";

        if (!keyword) return productBox.classList.add("d-none");

        const res = await fetch(`${API.products}?keyword=${keyword}`);
        const json = await res.json();
        const data = json.data || [];

        if (!data.length) return productBox.classList.add("d-none");

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
            quantity: 1,
            price: Number(p.sale_price || 0),
            base_price: Number(p.sale_price || 0),
            discount: 0,
            gift: false
        };

        render();
    }

    // =========================
    // RENDER (FIX ORDER + UI)
    // =========================
    function render() {

        tbody.innerHTML = "";

        const list = Object.values(selectedProducts)
            .filter(p => p && p.name)
            .sort((a, b) => (a.name || '').localeCompare(b.name || '', 'vi'));

        list.forEach(p => {

            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td>${p.name}</td>

                <td><input class="form-control form-control-sm qty" data-id="${p.id}" value="${p.quantity}"></td>

                <td><input class="form-control form-control-sm price" data-id="${p.id}" value="${p.price}" ${p.gift ? "disabled" : ""}></td>

                <td><input class="form-control form-control-sm discount" data-id="${p.id}" value="${p.discount}"></td>

                <td>
                    <input type="checkbox" class="form-check-input gift" data-id="${p.id}" ${p.gift ? "checked" : ""}>
                    Quà tặng
                </td>

                <td class="item-total" data-id="${p.id}"></td>

                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger remove" data-id="${p.id}">Xóa</button>
                </td>
            `;

            tbody.appendChild(tr);
        });

        bind();
        calc();
    }

    // =========================
    // BIND EVENTS (IMPORTANT FIX)
    // =========================
    function bind() {

        document.querySelectorAll(".qty").forEach(el => {
            el.oninput = () => {
                selectedProducts[el.dataset.id].quantity = +el.value || 1;
                calc();
            };
        });

        document.querySelectorAll(".price").forEach(el => {
            el.oninput = () => {
                selectedProducts[el.dataset.id].price = +el.value || 0;
                selectedProducts[el.dataset.id].base_price = +el.value || 0;
                calc();
            };
        });

        document.querySelectorAll(".discount").forEach(el => {
            el.oninput = () => {
                selectedProducts[el.dataset.id].discount = +el.value || 0;
                calc();
            };
        });

        document.querySelectorAll(".remove").forEach(el => {
            el.onclick = () => {
                delete selectedProducts[el.dataset.id];
                render();
            };
        });

        // GIFT FIX
        document.querySelectorAll(".gift").forEach(el => {
            el.onchange = () => {

                const p = selectedProducts[el.dataset.id];

                p.gift = el.checked;

                if (p.gift) {
                    p.price = 0;
                } else {
                    p.price = p.base_price;
                }

                render();
            };
        });
    }

    // =========================
    // CALC
    // =========================
    function calc() {

        let total = 0;

        Object.values(selectedProducts).forEach(p => {

            const sum = (p.price * p.quantity) - (p.discount || 0);

            const el = document.querySelector(`.item-total[data-id="${p.id}"]`);
            if (el) el.textContent = money(sum);

            total += sum;
        });

        totalEl.textContent = money(total);
    }

    // =========================
    // SUBMIT
    // =========================
    document.getElementById("order-create-form")
        .addEventListener("submit", async function (e) {

            e.preventDefault();

            const payload = {
                id: ORDER_ID,
                customer_id: customerId.value,
                description: document.getElementById("description").value,
                status: document.getElementById("status").value,
                payment: document.getElementById("payment").value,
                products: Object.values(selectedProducts)
            };

            const url = ORDER_ID ? "/api/orders/update" : "/api/orders";

            const res = await fetch(url, {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (!data.success) return alert(data.message || "Lỗi");

            alert("Cập nhật đơn hàng thành công!");
            window.location.href = "/admin/orders";
        });

    // INIT
    loadCustomersCache().then(loadOrder);

});
</script>