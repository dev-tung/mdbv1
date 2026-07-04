import State from './State.js';
import Api from './Api.js';

const Service = {

    /* =================================================
       LOAD
    ================================================= */

    async loadPurchase(id) {

        const response = await Api.getPurchase(id);

        State.purchase = response.data;

        this.recalc();
    },

    async searchSuppliers() {

        const response = await Api.searchSuppliers(State.supplier.keyword);

        State.supplier.suggestions = response.data || [];
    },

    async searchProducts() {

        const response = await Api.searchProducts(State.product.keyword);

        State.product.suggestions = response.data || [];
    },

    async loadWarehouses() {

        const response = await Api.getWarehouses();

        State.warehouse.list = response.data || [];
    },

    /* =================================================
       PURCHASE
    ================================================= */

    setSupplier(supplier) {

        State.purchase.supplier_id = supplier.id;
        State.purchase.supplier_name = supplier.name;

    },

    setWarehouse(id) {

        State.purchase.warehouse_id = Number(id);

    },

    setDescription(description) {

        State.purchase.description = description;

    },

    setStatus(status) {

        State.purchase.status = status;

    },

    setPayment(payment) {

        State.purchase.payment = payment;

        this.recalc();

    },

    setPaidAmount(amount) {

        State.purchase.paid_amount = Number(amount) || 0;

        this.recalc();

    },

    /* =================================================
       PRODUCT
    ================================================= */

    addProduct(product) {

        const exists = State.purchase.items.find(
            item => item.product_id === product.id
        );

        if (exists) {

            exists.quantity++;

        } else {

            State.purchase.items.push({

                product_id: product.id,

                name: product.name,

                quantity: product.quantity || 0,

                purchase_price: product.purchase_price || 0,

                order_price: product.order_price || 0,

                total_amount: product.total_amount || 0,

                vat_rate: product.vat_rate || 0,

                vat_amount: product.vat_amount || 0,

                total_amount_with_vat: product.total_amount_with_vat || 0

            });

        }

        this.recalc();

    },

    removeProduct(index) {

        State.purchase.items.splice(index, 1);

        this.recalc();

    },

    setQuantity(index, quantity) {

        const item = State.purchase.items[index];

        if (!item) return;

        item.quantity = Number(quantity) || 0;

        this.recalc();

    },

    setPurchasePrice(index, price) {

        const item = State.purchase.items[index];

        if (!item) return;

        item.purchase_price = Number(price) || 0;

        this.recalc();

    },

    setOrderPrice(index, value) {

        const item = State.purchase.items[index];

        if (!item) return;

        item.order_price = Number(value) || 0;

    },

    /* =================================================
       CALC
    ================================================= */

    recalc() {

        let total = 0;

        for (const item of State.purchase.items) {

            item.total_amount =
                (Number(item.quantity) || 0) *
                (Number(item.purchase_price) || 0);

            total += item.total_amount;

        }

        State.purchase.total_amount = total;

        const paid = Number(State.purchase.paid_amount) || 0;

        State.purchase.debt_amount = Math.max(total - paid, 0);

    },

    /* =================================================
       SAVE
    ================================================= */

    async save() {

        this.recalc();

        if (State.purchase.id) {

            return await Api.updatePurchase(
                State.purchase.id,
                State.purchase
            );

        }

        return await Api.createPurchase(State.purchase);

    }

};

export default Service;