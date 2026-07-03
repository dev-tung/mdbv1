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

        input.addEventListener("input", e => {
            PurchaseService.searchSupplier(e.target.value.trim());
        });

        document.addEventListener("click", e => {

            const item = e.target.closest(".supplier-item");

            if (!item) return;

            PurchaseService.setSupplier({
                id: Number(item.dataset.id),
                name: item.dataset.name
            });

        });

    },

    // =====================================================
    // PRODUCT
    // =====================================================

    bindProductSearch() {

        const input = document.getElementById("product_search");

        if (!input) return;

        input.addEventListener("input", e => {
            PurchaseService.searchProduct(e.target.value.trim());
        });

    },

    // =====================================================
    // WAREHOUSE
    // =====================================================

    bindWarehouseChange() {

        const select = document.getElementById("warehouse_id");

        if (!select) return;

        select.addEventListener("change", e => {

            PurchaseService.setWarehouse({
                id: Number(e.target.value),
                name: e.target.options[e.target.selectedIndex].text
            });

        });

    },

    // =====================================================
    // PAYMENT
    // =====================================================

    bindPayment() {

        document.getElementById("payment")?.addEventListener("change", e => {
            PurchaseService.setPaymentStatus(e.target.value);
        });

        document.getElementById("paid_amount")?.addEventListener("input", e => {
            PurchaseService.setPaidAmount(e.target.value);
        });

    },

    // =====================================================
    // PRODUCTS
    // =====================================================

    bindQuantity() {

        document.addEventListener("input", e => {

            if (!e.target.matches(".quantity")) return;

            PurchaseService.updateQuantity(
                Number(e.target.dataset.id),
                e.target.value
            );

        });

    },

    bindPrice() {

        document.addEventListener("input", e => {

            if (!e.target.matches(".price")) return;

            PurchaseService.updatePrice(
                Number(e.target.dataset.id),
                e.target.value
            );

        });

    },

    bindRemoveProduct() {

        document.addEventListener("click", e => {

            const button = e.target.closest(".remove-product");

            if (!button) return;

            PurchaseService.removeProduct(
                Number(button.dataset.id)
            );

        });

    },

    // =====================================================
    // SUBMIT
    // =====================================================

    bindSubmit() {

        const form = document.getElementById("purchase-create-form");

        if (!form) return;

        form.addEventListener("submit", async e => {

            e.preventDefault();

            await PurchaseService.create();

        });

    }

};