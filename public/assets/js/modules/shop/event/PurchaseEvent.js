// =========================================================
// modules/shop/event/PurchaseEvent.js
// =========================================================

import { PurchaseService } from "../service/PurchaseService.js";

export const PurchaseEvent = {

    init() {

        this.bindSupplierSearch();

        this.bindProductSearch();

        this.bindWarehouseChange();

        this.bindPayment();

        this.bindQuantity();

        this.bindPrice();

        this.bindRemoveProduct();

        this.bindSubmit();

    },

    // =====================================================
    // SUPPLIER
    // =====================================================

    bindSupplierSearch() {

        const input = document.getElementById("supplier_search");

        if (!input) return;

        input.addEventListener("input", (e) => {

            PurchaseService.searchSupplier(
                e.target.value
            );

        });

    },

    // =====================================================
    // PRODUCT
    // =====================================================

    bindProductSearch() {

        const input = document.getElementById("product_search");

        if (!input) return;

        input.addEventListener("input", (e) => {

            PurchaseService.searchProduct(
                e.target.value
            );

        });

    },

    // =====================================================
    // WAREHOUSE
    // =====================================================

    bindWarehouseChange() {

        const select = document.getElementById("warehouse_id");

        if (!select) return;

        select.addEventListener("change", (e) => {

            PurchaseService.changeWarehouse(
                e.target.value
            );

        });

    },

    // =====================================================
    // PAYMENT
    // =====================================================

    bindPayment() {

        const payment = document.getElementById("payment");

        if (!payment) return;

        payment.addEventListener("change", (e) => {

            PurchaseService.changePayment(
                e.target.value
            );

        });

    },

    // =====================================================
    // QUANTITY
    // =====================================================

    bindQuantity() {

        document.addEventListener("input", (e) => {

            if (!e.target.matches(".product-quantity")) {
                return;
            }

            PurchaseService.changeQuantity(

                e.target.dataset.id,

                e.target.value

            );

        });

    },

    // =====================================================
    // PRICE
    // =====================================================

    bindPrice() {

        document.addEventListener("input", (e) => {

            if (!e.target.matches(".product-price")) {
                return;
            }

            PurchaseService.changePrice(

                e.target.dataset.id,

                e.target.value

            );

        });

    },

    // =====================================================
    // REMOVE PRODUCT
    // =====================================================

    bindRemoveProduct() {

        document.addEventListener("click", (e) => {

            const button = e.target.closest(".btn-remove-product");

            if (!button) return;

            PurchaseService.removeProduct(

                button.dataset.id

            );

        });

    },

    // =====================================================
    // SUBMIT
    // =====================================================

    bindSubmit() {

        const form = document.getElementById(
            "purchase-create-form"
        );

        if (!form) return;

        form.addEventListener("submit", async (e) => {

            e.preventDefault();

            await PurchaseService.create();

        });

    }

};