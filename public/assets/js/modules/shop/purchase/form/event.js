// =========================================================
// modules/shop/purchase/form/event.js
// =========================================================

import { service } from "./service.js";

export const event = {

    init() {

        this.supplierSearch();
        this.warehouseChange();
        this.paymentChange();
        this.productSearch();
        this.productQuantity();
        this.productPrice();
        this.productRemove();
        this.formSubmit();

    },

    // =====================================================
    // SUPPLIER
    // =====================================================

    supplierSearch() {

        const input = document.getElementById("supplier_search");

        if (!input) return;

        input.addEventListener("input", e => {

            service.searchSupplier(
                e.target.value.trim()
            );

        });

        document.addEventListener("click", e => {

            const item = e.target.closest(".supplier-item");

            if (!item) return;

            service.setSupplier({
                id: Number(item.dataset.id),
                name: item.dataset.name
            });

        });

    },

    // =====================================================
    // PRODUCT
    // =====================================================

    productSearch() {

        const input = document.getElementById("product_search");

        if (!input) return;

        input.addEventListener("input", e => {

            service.searchProduct(
                e.target.value.trim()
            );

        });

    },

    productQuantity() {

        document.addEventListener("input", e => {

            if (!e.target.matches(".quantity")) return;

            service.updateQuantity(

                Number(e.target.dataset.id),

                e.target.value

            );

        });

    },

    productPrice() {

        document.addEventListener("input", e => {

            if (!e.target.matches(".price")) return;

            service.updatePrice(

                Number(e.target.dataset.id),

                e.target.value

            );

        });

    },

    productRemove() {

        document.addEventListener("click", e => {

            const button = e.target.closest(".remove-product");

            if (!button) return;

            service.removeProduct(

                Number(button.dataset.id)

            );

        });

    },

    // =====================================================
    // WAREHOUSE
    // =====================================================

    warehouseChange() {

        const select = document.getElementById("warehouse_id");

        if (!select) return;

        select.addEventListener("change", e => {

            service.setWarehouse({

                id: Number(e.target.value),

                name: e.target.options[e.target.selectedIndex].text

            });

        });

    },

    // =====================================================
    // PAYMENT
    // =====================================================

    paymentChange() {

        document.getElementById("payment")?.addEventListener("change", e => {

            service.setPaymentStatus(
                e.target.value
            );

        });

        document.getElementById("paid_amount")?.addEventListener("input", e => {

            service.setPaidAmount(
                e.target.value
            );

        });

    },

    // =====================================================
    // FORM
    // =====================================================

    formSubmit() {

        const form = document.getElementById("purchase-create-form");

        if (!form) return;

        form.addEventListener("submit", async e => {

            e.preventDefault();

            await service.create();

        });

    }

};