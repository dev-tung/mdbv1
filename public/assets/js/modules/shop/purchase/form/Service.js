import State from './State.js';
import Api from './Api.js';
import Calculator from '/assets/js/common/Calculator.js';

const Service = {

    /* =================================================
       LOAD
    ================================================= */

    async loadPurchase(id) {

        const response = await Api.showPurchase(id);

        const [purchase, items] = response.data;

        Object.assign(
            State.purchase,
            purchase[0] || {}
        );

        State.purchase.items = items || [];

        this.setMoneyOverall();

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

        State.purchase.warehouse_id = Number(id) || null;

    },

    setDescription(description) {

        State.purchase.description = description;

    },

    setStatus(status) {

        State.purchase.status = status;

    },

    setPayment(payment) {

        State.purchase.payment = payment;

        this.setMoneyOverall();

    },

    setPaidAmount(amount) {

        State.purchase.paid_amount = Number(amount) || 0;

        this.setMoneyOverall();

    },

    /* =================================================
       PRODUCT
    ================================================= */

    addProduct(product) {

        const exists = State.purchase.items.find(
            item => item.product_id === product.id
        );

        if (exists) {

            exists.quantity = (Number(exists.quantity) || 0) + 1;

        } else {

            State.purchase.items.push({

                product_id: product.id,
                product_name: product.name,

                quantity: 1,
                purchase_price: 0,
                order_price: 0,

                vat_rate: product.vat_rate || 0,

                total_amount: 0,
                vat_amount: 0,
                total_amount_with_vat: 0

            });

        }

        this.setMoneyOverall();

    },

    removeProduct(index) {

        State.purchase.items.splice(index, 1);

        this.setMoneyOverall();

    },

    setQuantity(index, quantity) {

        const item = State.purchase.items[index];
        if (!item) return;

        item.quantity = Number(quantity) || 0;

        this.setMoneyOverall();

    },

    setPurchasePrice(index, price) {

        const item = State.purchase.items[index];
        if (!item) return;

        item.purchase_price = Number(price) || 0;

        this.setMoneyOverall();

    },

    setOrderPrice(index, price) {

        const item = State.purchase.items[index];
        if (!item) return;

        item.order_price = Number(price) || 0;

        this.setMoneyOverall();

    },

    setVatRate(index, vatRate) {

        const item = State.purchase.items[index];
        if (!item) return;

        item.vat_rate = Number(vatRate) || 0;

        this.setMoneyOverall();

    },

    /* =================================================
       CALCULATE
    ================================================= */

    setMoneyOverall() {

        let totalAmount = 0;
        let vatAmount = 0;

        for (const item of State.purchase.items) {

            item.total_amount = Calculator.amount(
                item.quantity,
                item.purchase_price
            );

            item.vat_amount = Calculator.vat(
                item.total_amount,
                item.vat_rate
            );

            item.total_amount_with_vat = Calculator.total(
                item.total_amount,
                item.vat_amount
            );

            totalAmount += item.total_amount;
            vatAmount += item.vat_amount;

        }

        State.purchase.total_amount = totalAmount;

        State.purchase.vat_amount = vatAmount;

        State.purchase.total_amount_with_vat = Calculator.total(
            totalAmount,
            vatAmount
        );

        State.purchase.debt_amount = Calculator.debt(
            State.purchase.total_amount_with_vat,
            State.purchase.paid_amount
        );

    },

    /* =================================================
       SAVE
    ================================================= */

    async save() {

        this.setMoneyOverall();

        if (State.purchase.id) {

            return await Api.updatePurchase(
                State.purchase.id,
                State.purchase
            );

        }

        const response = await Api.createPurchase(State.purchase);

        alert(response.message);
        if (response.success) {
            window.location.href = response.redirect;
        }

    }

};

export default Service;