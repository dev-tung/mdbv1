// =========================================================
// modules/shop/service/PurchaseService.js
// =========================================================

import { PurchaseDetailState } from "../state/PurchaseState.js";

export const PurchaseService = {

    // =====================================================
    // SUPPLIER
    // =====================================================

    setSupplier(supplier) {

        PurchaseDetailState.supplier = {
            id: supplier.id,
            name: supplier.name
        };

    },

    // =====================================================
    // WAREHOUSE
    // =====================================================

    setWarehouse(warehouse) {

        PurchaseDetailState.warehouse = {
            id: warehouse.id,
            name: warehouse.name
        };

    },

    // =====================================================
    // PRODUCT
    // =====================================================

    addProduct(product) {

        const exists = PurchaseDetailState.products.find(
            item => item.product_id === product.id
        );

        if (exists) {

            exists.quantity++;

            exists.subtotal = exists.quantity * exists.price;

            this.calculateSummary();

            return;

        }

        PurchaseDetailState.products.push({

            product_id: product.id,

            name: product.name,

            quantity: 1,

            price: product.price,

            subtotal: product.price

        });

        this.calculateSummary();

    },

    removeProduct(productId) {

        PurchaseDetailState.products =
            PurchaseDetailState.products.filter(

                item => item.product_id !== productId

            );

        this.calculateSummary();

    },

    updateQuantity(productId, quantity) {

        const item = PurchaseDetailState.products.find(

            p => p.product_id === productId

        );

        if (!item) return;

        item.quantity = Number(quantity);

        item.subtotal = item.quantity * item.price;

        this.calculateSummary();

    },

    updatePrice(productId, price) {

        const item = PurchaseDetailState.products.find(

            p => p.product_id === productId

        );

        if (!item) return;

        item.price = Number(price);

        item.subtotal = item.quantity * item.price;

        this.calculateSummary();

    },

    // =====================================================
    // PAYMENT
    // =====================================================

    setPayment(method) {

        PurchaseDetailState.payment.method = method;

    },

    setPaymentStatus(status) {

        PurchaseDetailState.payment.status = status;

    },

    setPaidAmount(amount) {

        PurchaseDetailState.payment.paid_amount = Number(amount);

        this.calculateSummary();

    },

    // =====================================================
    // SUMMARY
    // =====================================================

    calculateSummary() {

        let total = 0;

        PurchaseDetailState.products.forEach(item => {

            item.subtotal = item.quantity * item.price;

            total += item.subtotal;

        });

        PurchaseDetailState.summary.total_amount = total;

        PurchaseDetailState.summary.debt_amount = total - PurchaseDetailState.payment.paid_amount;

        if (PurchaseDetailState.summary.debt_amount < 0) {

            PurchaseDetailState.summary.debt_amount = 0;

        }

    },

    // =====================================================
    // BUILD PAYLOAD
    // =====================================================

    buildPayload() {

        return {

            supplier_id:
                PurchaseDetailState.supplier.id,

            warehouse_id:
                PurchaseDetailState.warehouse.id,

            status:
                PurchaseDetailState.meta.status,

            payment:
                PurchaseDetailState.payment.status,

            description:
                PurchaseDetailState.meta.description,

            paid_amount:
                PurchaseDetailState.payment.paid_amount,

            products:
                PurchaseDetailState.products.map(item => ({

                    product_id: item.product_id,

                    quantity: item.quantity,

                    price: item.price

                }))

        };

    }

};