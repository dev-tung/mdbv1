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
                        id: product.id,
                        name: product.name,
                        quantity: 1,
                        price: Number(product.price || 0)
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

        const existed = products.find(p => p.id == product.id);

        if (existed) {
            existed.quantity += 1;
        } else {
            products.push(product);
        }

        this.updateState();
    },

    // =========================
    // REMOVE
    // =========================
    remove(id) {

        products = products.filter(p => p.id != id);

        this.updateState();
    },

    // =========================
    // UPDATE QUANTITY
    // =========================
    updateQuantity(id, quantity) {

        const product = products.find(p => p.id == id);
        if (!product) return;

        product.quantity = Math.max(1, parseInt(quantity) || 1);

        this.updateRow(id);
    },

    // =========================
    // UPDATE PRICE
    // =========================
    updatePrice(id, price) {

        const product = products.find(p => p.id == id);
        if (!product) return;

        product.price = Math.max(0, parseFloat(price) || 0);

        this.updateRow(id);
    },

    // =========================
    // UPDATE SINGLE ROW (FIX TOTAL LINE)
    // =========================
    updateRow(id) {

        const product = products.find(p => String(p.id) === String(id));
        if (!product) return;

        const row = document.querySelector(`[data-id="${id}"]`)?.closest("tr");
        if (!row) return;

        const totalCell = row.querySelector(".item-total");

        const qty = Number(product.quantity) || 0;
        const price = Number(product.price) || 0;

        const total = qty * price;

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
                    <td>${product.name}</td>

                    <td width="150">
                        <input type="number" min="1"
                            class="form-control quantity-input"
                            data-id="${product.id}"
                            value="${product.quantity}">
                    </td>

                    <td width="180">
                        <input type="number" min="0"
                            class="form-control price-input"
                            data-id="${product.id}"
                            value="${product.price}">
                    </td>

                    <td class="item-total">
                        ${total.toLocaleString()}
                    </td>

                    <td width="100">
                        <button type="button"
                            class="btn btn-sm btn-outline-danger remove-btn"
                            data-id="${product.id}">
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

        const total = this.getTotal();

        const el = document.getElementById("total_amount");
        if (el) el.textContent = total.toLocaleString();
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
        window.dispatchEvent(new Event("purchase:update"));
    },

    getItems() {
        return products;
    },

    setItems(items = []) {

        products = items.map(item => ({
            id: item.product_id || item.id,
            name: item.product_name || item.name,
            quantity: Number(item.quantity || 1),
            price: Number(item.price || 0)
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