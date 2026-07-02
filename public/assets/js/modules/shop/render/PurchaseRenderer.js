// =========================================================
// modules/shop/render/PurchaseRenderer.js
// =========================================================

import { PurchaseDetailState } from "../state/PurchaseState.js";

export const PurchaseRenderer = {

    // =====================================================
    // Render toàn bộ
    // =====================================================

    render() {

        this.renderSupplier();

        this.renderWarehouse();

        this.renderProducts();

        this.renderPayment();

        this.renderSummary();

    },

    // =====================================================
    // Supplier
    // =====================================================

    renderSupplier() {

        document.getElementById("supplier_id").value =
            PurchaseDetailState.supplier.id ?? "";

        document.getElementById("supplier_search").value =
            PurchaseDetailState.supplier.name ?? "";

    },

    // =====================================================
    // Warehouse
    // =====================================================

    renderWarehouse() {

    const select = document.getElementById("warehouse_id");

    select.innerHTML = PurchaseDetailState.warehouses
        .map(item => `
            <option
                value="${item.id}"
                ${item.id == PurchaseDetailState.warehouse.id ? "selected" : ""}
            >
                ${item.name}
            </option>
        `)
        .join("");

    },

    // =====================================================
    // Product Table
    // =====================================================

    renderProducts() {

        const tbody = document.getElementById("selected_products");

        tbody.innerHTML = "";

        PurchaseDetailState.products.forEach(product => {

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

                    <td>

                        ${product.subtotal.toLocaleString()} ₫

                    </td>

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

    renderPayment() {

        document.getElementById("payment").value =
            PurchaseDetailState.payment.status;

        document.getElementById("paid_amount").value =
            PurchaseDetailState.payment.paid_amount;

        const wrapper = document.getElementById("paid_amount_wrapper");

        wrapper.classList.toggle(
            "d-none",
            PurchaseDetailState.payment.status === "unpaid"
        );

    },

    // =====================================================
    // Summary
    // =====================================================

    renderSummary() {

        document.getElementById("total_amount").textContent =
            PurchaseDetailState.summary.total_amount.toLocaleString();

        document.getElementById("paid_amount_view").textContent =
            PurchaseDetailState.payment.paid_amount.toLocaleString();

        document.getElementById("debt_amount_view").textContent =
            PurchaseDetailState.summary.debt_amount.toLocaleString();

    }

};