import { Api } from "../../../common/api.js";

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

    add(product) {

        const existed = products.find(p => p.id == product.id);

        if (existed) {
            existed.quantity += 1;
        } else {
            products.push(product);
        }

        this.render();
        this.emitUpdate();
    },

    remove(id) {

        products = products.filter(p => p.id != id);

        this.render();
        this.emitUpdate();
    },

    updateQuantity(id, quantity) {

        const product = products.find(p => p.id == id);
        if (!product) return;

        product.quantity = Math.max(1, parseInt(quantity) || 1);

        this.updateState();
    },

    updatePrice(id, price) {

        const product = products.find(p => p.id == id);
        if (!product) return;

        product.price = Math.max(0, parseFloat(price) || 0);

        this.updateState();
    },

    // =========================
    // SAFE UPDATE (NO REBUILD LOOP)
    // =========================
    updateState() {

        this.renderTotal();
        this.emitUpdate();
    },

    emitUpdate() {
        window.dispatchEvent(new Event("purchase:update"));
    },

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
                            class="btn btn-sm btn-danger remove-btn"
                            data-id="${product.id}">
                            Xóa
                        </button>
                    </td>
                </tr>
            `);
        });

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