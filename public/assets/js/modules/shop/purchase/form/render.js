// =========================================================
// modules/shop/purchase/form/render.js
// =========================================================

import { state } from "./state.js";

export const render = {

    // =====================================================
    // Init
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
    // Supplier
    // =====================================================

    supplier() {

        document.getElementById("supplier_id").value =
            state.supplier.selected.id ?? "";

        document.getElementById("supplier_search").value =
            state.supplier.selected.name ?? "";

    },

    // =====================================================
    // Supplier Dropdown
    // =====================================================

    supplierDropdown() {

        const container = document.getElementById("supplier_suggestions");

        if (!container) return;

        const suppliers = state.supplier.search.results;

        if (!suppliers.length) {

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
    // Warehouse
    // =====================================================

    warehouse() {

        const select = document.getElementById("warehouse_id");

        select.innerHTML = state.warehouse.list.map(item => `
            <option
                value="${item.id}"
                ${item.id == state.warehouse.selected.id ? "selected" : ""}>
                ${item.name}
            </option>
        `).join("");

    },

    // =====================================================
    // Products
    // =====================================================

    products() {

        const tbody = document.getElementById("selected_products");

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

                    <td>${product.subtotal.toLocaleString()} ₫</td>

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
    // Payment
    // =====================================================

    payment() {

        document.getElementById("payment").value =
            state.payment.status;

        document.getElementById("paid_amount").value =
            state.payment.paid_amount;

        document.getElementById("paid_amount_wrapper").classList.toggle(
            "d-none",
            state.payment.status === "unpaid"
        );

    },

    // =====================================================
    // Summary
    // =====================================================

    summary() {

        document.getElementById("total_amount").textContent =
            state.summary.total_amount.toLocaleString();

        document.getElementById("paid_amount").textContent =
            state.payment.paid_amount.toLocaleString();

        document.getElementById("debt_amount").textContent =
            state.summary.debt_amount.toLocaleString();

    }

};