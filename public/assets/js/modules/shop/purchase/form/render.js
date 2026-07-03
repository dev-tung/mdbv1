// =========================================================
// modules/shop/purchase/form/render.js
// =========================================================

import { state } from "./state.js";

export const render = {

    // =====================================================
    // INIT
    // =====================================================

    init() {

        this.supplier();
        this.supplierDropdown();
        this.warehouse();
        this.products();
        this.payment();
        this.summary();

    },

    // =====================================================
    // SUPPLIER
    // =====================================================

    supplier() {

        const idEl = document.getElementById("supplier_id");
        const searchEl = document.getElementById("supplier_search");

        if (idEl) idEl.value = state.supplier.selected.id ?? "";
        if (searchEl) searchEl.value = state.supplier.selected.name ?? "";

    },

    supplierDropdown() {

        const container = document.getElementById("supplier_suggestions");
        if (!container) return;

        const suppliers = state.supplier.search.results;

        if (!suppliers || suppliers.length === 0) {
            container.innerHTML = "";
            container.classList.add("d-none");
            return;
        }

        container.innerHTML = suppliers.map(item => `
            <button
                type="button"
                class="list-group-item list-group-item-action supplier-item"
                data-id="${item.id}"
                data-name="${item.name}">
                ${item.name}
            </button>
        `).join("");

        container.classList.remove("d-none");

    },

    // =====================================================
    // WAREHOUSE
    // =====================================================

    warehouse() {

        const select = document.getElementById("warehouse_id");
        if (!select) return;

        select.innerHTML = state.warehouse.list.map(item => `
            <option
                value="${item.id}"
                ${item.id == state.warehouse.selected.id ? "selected" : ""}>
                ${item.name}
            </option>
        `).join("");

    },

    // =====================================================
    // PRODUCTS
    // =====================================================

    products() {

        const tbody = document.getElementById("selected_products");
        if (!tbody) return;

        tbody.innerHTML = "";

        state.products.items.forEach(product => {

            tbody.insertAdjacentHTML("beforeend", `
                <tr data-id="${product.product_id}">
                    <td>${product.name}</td>

                    <td>
                        <input
                            type="number"
                            class="form-control quantity"
                            data-id="${product.product_id}"
                            value="${product.quantity}"
                            min="1">
                    </td>

                    <td>
                        <input
                            type="number"
                            class="form-control price"
                            data-id="${product.product_id}"
                            value="${product.price}"
                            min="0">
                    </td>

                    <td>${Number(product.subtotal || 0).toLocaleString()} ₫</td>

                    <td>
                        <button
                            type="button"
                            class="btn btn-sm btn-danger remove-product"
                            data-id="${product.product_id}">
                            Xóa
                        </button>
                    </td>
                </tr>
            `);

        });

    },

    // =====================================================
    // PAYMENT
    // =====================================================

    payment() {

        const paymentEl = document.getElementById("payment");
        const paidEl = document.getElementById("paid_amount");
        const wrapper = document.getElementById("paid_amount_wrapper");

        if (paymentEl) {
            paymentEl.value = state.payment.status;
        }

        if (paidEl) {
            paidEl.value = state.payment.paid_amount;
        }

        if (wrapper) {
            wrapper.classList.toggle(
                "d-none",
                state.payment.status === "unpaid"
            );
        }

    },

    // =====================================================
    // SUMMARY (FIX NULL ERROR HERE)
    // =====================================================

    summary() {

        const totalEl = document.getElementById("total_amount");
        const paidEl = document.getElementById("paid_amount");
        const debtEl = document.getElementById("debt_amount");

        if (totalEl) {
            totalEl.textContent =
                Number(state.summary.total_amount || 0).toLocaleString();
        }

        if (paidEl) {
            paidEl.textContent =
                Number(state.payment.paid_amount || 0).toLocaleString();
        }

        if (debtEl) {
            debtEl.textContent =
                Number(state.summary.debt_amount || 0).toLocaleString();
        }

    }

};