import { Api } from "../../../../common/api.js";

let products = [];

export const Product = {

    init(url) {

        const input = document.getElementById("product_search");
        const box = document.getElementById("product_suggestions");

        if (!input || !box) return;

        input.addEventListener("input", async () => {

            const keyword = input.value.trim();

            if (!keyword) {
                box.classList.add("d-none");
                box.innerHTML = "";
                return;
            }

            const json = await Api.get(
                `${url}?keyword=${encodeURIComponent(keyword)}`
            );

            if (!json.success) return;

            box.innerHTML = "";

            json.data.forEach(product => {

                const item = document.createElement("button");

                item.type = "button";
                item.className = "list-group-item list-group-item-action";
                item.textContent = product.name;

                item.onclick = () => {

                    this.add({
                        product_id: product.product_id || product.id,
                        name: product.name,
                        quantity: 1,
                        price: Number(product.price || 0),
                        purchase_item_id: product.purchase_item_id
                    });

                    input.value = "";
                    box.innerHTML = "";
                    box.classList.add("d-none");
                };

                box.appendChild(item);
            });

            box.classList.remove("d-none");
        });
    },

    // =========================
    // ADD PRODUCT
    // =========================
    add(product) {

        const existed = products.find(p => p.product_id == product.product_id);

        if (existed) {
            existed.quantity += 1;
        } else {
            products.push({
                product_id: product.product_id,
                name: product.name,
                quantity: product.quantity,
                price: product.price,
                purchase_item_id: product.purchase_item_id
            });
        }

        this.updateState();
    },

    // =========================
    // REMOVE
    // =========================
    remove(product_id) {

        products = products.filter(
            p => String(p.product_id) !== String(product_id)
        );

        this.updateState();
    },

    // =========================
    // UPDATE QUANTITY
    // =========================
    updateQuantity(product_id, quantity) {

        const product = products.find(
            p => String(p.product_id) === String(product_id)
        );

        if (!product) return;

        product.quantity = Math.max(1, parseInt(quantity) || 1);

        this.updateRow(product_id);
    },

    // =========================
    // UPDATE PRICE
    // =========================
    updatePrice(product_id, price) {

        const product = products.find(
            p => String(p.product_id) === String(product_id)
        );

        if (!product) return;

        product.price = Math.max(0, parseFloat(price) || 0);

        this.updateRow(product_id);
    },

    // =========================
    // UPDATE ROW
    // =========================
    updateRow(product_id) {

        const product = products.find(
            p => String(p.product_id) === String(product_id)
        );

        if (!product) return;

        const row = document
            .querySelector(`[data-id="${product_id}"]`)
            ?.closest("tr");

        if (!row) return;

        const totalCell = row.querySelector(".item-total");

        const total =
            (product.quantity || 0) * (product.price || 0);

        if (totalCell) {
            totalCell.textContent = total.toLocaleString();
        }

        this.renderTotal();
        this.emitUpdate();
    },

    // =========================
    // RENDER TABLE
    // =========================
    render() {

        const tbody = document.getElementById("selected_products");
        if (!tbody) return;

        tbody.innerHTML = "";

        products.forEach(product => {

            const total = product.quantity * product.price;

            tbody.insertAdjacentHTML("beforeend", `
                <tr>
                    <td>
                        ${product.name}

                        <input type="hidden"
                               class="purchase-item-id"
                               data-id="${product.product_id}"
                               value="${product.purchase_item_id || ''}">
                    </td>

                    <td width="150">
                        <input type="number" min="1"
                            class="form-control quantity-input"
                            data-id="${product.product_id}"
                            value="${product.quantity}">
                    </td>

                    <td width="180">
                        <input type="number" min="0"
                            class="form-control price-input"
                            data-id="${product.product_id}"
                            value="${product.price}">
                    </td>

                    <td class="item-total">
                        ${total.toLocaleString()}
                    </td>

                    <td width="100">
                        <button type="button"
                            class="btn btn-sm btn-outline-danger remove-btn"
                            data-id="${product.product_id}">
                            Xóa
                        </button>
                    </td>
                </tr>
            `);
        });

        // events
        document.querySelectorAll(".remove-btn").forEach(btn => {
            btn.onclick = () => this.remove(btn.dataset.id);
        });

        document.querySelectorAll(".quantity-input").forEach(input => {
            input.oninput = () => {
                this.updateQuantity(input.dataset.id, input.value);
            };
        });

        document.querySelectorAll(".price-input").forEach(input => {
            input.oninput = () => {
                this.updatePrice(input.dataset.id, input.value);
            };
        });

        this.renderTotal();
    },

    // =========================
    // TOTAL
    // =========================
    renderTotal() {

        const el = document.getElementById("total_amount");
        if (el) {
            el.textContent = this.getTotal().toLocaleString();
        }
    },

    getTotal() {

        return products.reduce((sum, item) => {
            return sum + (item.quantity * item.price);
        }, 0);
    },

    // =========================
    // STATE
    // =========================
    updateState() {
        this.render();
        this.renderTotal();
        this.emitUpdate();
    },

    emitUpdate() {
        window.dispatchEvent(new Event("order:update"));
    },

    // =========================
    // OUTPUT FOR BACKEND
    // =========================
    getItems() {

        return products.map(item => ({
            product_id: item.product_id,
            quantity: Number(item.quantity),
            price: Number(item.price),
            purchase_item_id: item.purchase_item_id
        }));
    },

    setItems(items = []) {

        products = items.map(item => ({
            product_id: item.product_id,
            name: item.product_name || item.name,
            quantity: Number(item.quantity || 1),
            price: Number(item.unit_price ?? item.price ?? 0),
            purchase_item_id: item.purchase_item_id
        }));

        this.render();
        this.emitUpdate();
    },

    clear() {
        products = [];
        this.render();
        this.emitUpdate();
    }
};