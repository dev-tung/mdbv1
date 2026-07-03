// =========================================================
// modules/shop/service/PurchaseService.js
// =========================================================

import { PurchaseRenderer } from "../render/PurchaseRenderer.js";
import { state } from "../state/PurchaseState.js";
import { SupplierApi } from "../api/SupplierApi.js";

export const PurchaseService = {

    // =====================================================
    // SUPPLIER
    // =====================================================

    setSupplier(supplier) {

        state.supplier.selected = {
            id: supplier.id,
            name: supplier.name
        };

        state.supplier.search.results = [];

        PurchaseRenderer.renderSupplier();
        PurchaseRenderer.renderSupplierDropdown();

    },

    async searchSupplier(keyword = "") {

        try {

            const response = await SupplierApi.getList({ keyword });

            state.supplier.search.results = response.data;

            PurchaseRenderer.renderSupplierDropdown();

        } catch (error) {

            console.error(error);

            state.supplier.search.results = [];

        }

    },

    // =====================================================
    // WAREHOUSE
    // =====================================================

    setWarehouse(warehouse) {

        state.warehouse.selected = {
            id: warehouse.id,
            name: warehouse.name
        };

    },

    // =====================================================
    // PRODUCTS
    // =====================================================

    addProduct(product) {

        const exists = state.products.items.find(
            item => item.product_id === product.id
        );

        if (exists) {

            exists.quantity++;
            exists.subtotal = exists.quantity * exists.price;

            this.calculateSummary();

            return;

        }

        state.products.items.push({

            product_id: product.id,
            name: product.name,

            quantity: 1,
            price: product.price,
            subtotal: product.price

        });

        this.calculateSummary();

    },

    removeProduct(productId) {

        state.products.items = state.products.items.filter(
            item => item.product_id !== productId
        );

        this.calculateSummary();

    },

    updateQuantity(productId, quantity) {

        const item = state.products.items.find(
            item => item.product_id === productId
        );

        if (!item) return;

        item.quantity = Number(quantity);
        item.subtotal = item.quantity * item.price;

        this.calculateSummary();

    },

    updatePrice(productId, price) {

        const item = state.products.items.find(
            item => item.product_id === productId
        );

        if (!item) return;

        item.price = Number(price);
        item.subtotal = item.quantity * item.price;

        this.calculateSummary();

    },

    // =====================================================
    // PAYMENT
    // =====================================================

    setPaymentStatus(status) {

        state.payment.status = status;

    },

    setPaidAmount(amount) {

        state.payment.paid_amount = Number(amount);

        this.calculateSummary();

    },

    // =====================================================
    // SUMMARY
    // =====================================================

    calculateSummary() {

        let total = 0;

        state.products.items.forEach(item => {

            item.subtotal = item.quantity * item.price;

            total += item.subtotal;

        });

        state.summary.total_amount = total;

        state.summary.debt_amount = Math.max(
            0,
            total - state.payment.paid_amount
        );

    },

    // =====================================================
    // BUILD PAYLOAD
    // =====================================================

    buildPayload() {

        return {

            supplier_id: state.supplier.selected.id,

            warehouse_id: state.warehouse.selected.id,

            status: state.meta.status,

            payment: state.payment.status,

            description: state.meta.description,

            paid_amount: state.payment.paid_amount,

            products: state.products.items.map(item => ({
                product_id: item.product_id,
                quantity: item.quantity,
                price: item.price
            }))

        };

    }

};