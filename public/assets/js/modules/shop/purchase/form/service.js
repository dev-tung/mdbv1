// =========================================================
// modules/shop/purchase/form/service.js
// =========================================================

import { api } from "../../api.js";
import { render } from "./render.js";
import { state } from "./state.js";

export const service = {

    // =====================================================
    // SUPPLIER
    // =====================================================

    supplierSelect(item) {

        state.supplier.selected = {
            id: item.id,
            name: item.name
        };

        state.supplier.search.results = [];

        render.supplier();
        render.supplierDropdown();

    },

    async supplierSearch(keyword = "") {

        try {

            const response = await api.supplier.getList({ keyword });

            state.supplier.search.results = response.data;

        } catch (error) {

            console.error(error);

            state.supplier.search.results = [];

        }

        render.supplierDropdown();

    },

    // =====================================================
    // WAREHOUSE
    // =====================================================

    warehouseSelect(item) {

        state.warehouse.selected = {
            id: item.id,
            name: item.name
        };

        render.warehouse();

    },

    // =====================================================
    // PRODUCT
    // =====================================================

    productAdd(product) {

        const item = state.products.items.find(
            p => p.product_id === product.id
        );

        if (item) {

            item.quantity++;

        } else {

            state.products.items.push({
                product_id: product.id,
                name: product.name,
                quantity: 1,
                price: product.price,
                subtotal: product.price
            });

        }

        this.calculateSummary();

        render.products();
        render.summary();

    },

    productRemove(productId) {

        state.products.items = state.products.items.filter(
            item => item.product_id !== productId
        );

        this.calculateSummary();

        render.products();
        render.summary();

    },

    productQuantity(productId, quantity) {

        const item = state.products.items.find(
            item => item.product_id === productId
        );

        if (!item) return;

        item.quantity = Number(quantity);

        this.calculateSummary();

        render.products();
        render.summary();

    },

    productPrice(productId, price) {

        const item = state.products.items.find(
            item => item.product_id === productId
        );

        if (!item) return;

        item.price = Number(price);

        this.calculateSummary();

        render.products();
        render.summary();

    },

    // =====================================================
    // PAYMENT
    // =====================================================

    paymentStatus(status) {

        state.payment.status = status;

        render.payment();

    },

    paymentAmount(amount) {

        state.payment.paid_amount = Number(amount);

        this.calculateSummary();

        render.summary();

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