import State from './State.js';
import Api from './Api.js';
import Calculator from '/assets/js/common/Calculator.js';

const Service = {

    /* =================================================
       LOAD
    ================================================= */

    async loadOrder(id) {

        const response = await Api.showOrder(id);

        const [order, items] = response.data;

        Object.assign(
            State.order,
            order[0] || {}
        );

        State.order.items = items || [];

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

    /* =================================================
       ORDER
    ================================================= */

    setSupplier(supplier) {

        State.order.supplier_id = supplier.id;
        State.order.supplier_name = supplier.name;

    },

    setDescription(description) {

        State.order.description = description;

    },

    setStatus(status) {

        State.order.status = status;

    },

    setPayment(payment) {

        State.order.payment = payment;

        this.setMoneyOverall();

    },

    setPaidAmount(amount) {

        State.order.paid_amount = Number(amount) || 0;

        this.setMoneyOverall();

    },

    /* =================================================
       PRODUCT
    ================================================= */

    addProduct(product) {

        const exists = State.order.items.find(
            item => item.product_id === product.id
        );

        if (exists) {

            exists.quantity = (Number(exists.quantity) || 0) + 1;

        } else {

            State.order.items.push({

                product_id: product.id,
                product_name: product.name,

                quantity: 1,
                order_price: 0,
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

        State.order.items.splice(index, 1);

        this.setMoneyOverall();

    },

    setQuantity(index, quantity) {

        const item = State.order.items[index];
        if (!item) return;

        item.quantity = Number(quantity) || 0;

        this.setMoneyOverall();

    },

    setOrderPrice(index, price) {

        const item = State.order.items[index];
        if (!item) return;

        item.order_price = Number(price) || 0;

        this.setMoneyOverall();

    },

    setOrderPrice(index, price) {

        const item = State.order.items[index];
        if (!item) return;

        item.order_price = Number(price) || 0;

        this.setMoneyOverall();

    },

    setVatRate(index, vatRate) {

        const item = State.order.items[index];
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

        for (const item of State.order.items) {

            item.total_amount = Calculator.amount(
                item.quantity,
                item.order_price
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

        State.order.total_amount = totalAmount;

        State.order.vat_amount = vatAmount;

        State.order.total_amount_with_vat = Calculator.total(
            totalAmount,
            vatAmount
        );

        State.order.debt_amount = Calculator.debt(
            State.order.total_amount_with_vat,
            State.order.paid_amount
        );

    },

    /* =================================================
       SAVE
    ================================================= */

    async save() {

        this.setMoneyOverall();

        if (State.order.id) {

            return await Api.updateOrder(
                State.order.id,
                State.order
            );
        }

        return await Api.createOrder(State.order);
    }

};

export default Service;