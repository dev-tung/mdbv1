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

    
    setNote(note) {

        State.purchase.note = note;

    },

    setStatus(status) {

        State.purchase.status = status;

    },

    setPayment(payment) {

        State.purchase.payment = payment;

    },

    setVatRate(rate) {

        State.purchase.vat_rate = Number(rate) || 0;

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

            exists.quantity++;

        } else {

            State.purchase.items.push({

                product_id: product.id,
                product_name: product.name,

                quantity: 1,

                purchase_price: 0,
                selling_price: Number(product.sale_price) || 0,

                subtotal_amount: 0,

                vat_rate: 0,
                vat_amount: 0,

                total_amount: 0,
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

    setSellingPrice(index, price) {

        const item = State.purchase.items[index];

        if (!item) return;

        item.selling_price = Number(price) || 0;

    },

    /* =================================================
       CALCULATE
    ================================================= */

    setMoneyOverall() {

        let subtotalAmount = 0;
        let vatAmount = 0;
        let totalAmount = 0;

        for (const item of State.purchase.items) {

            item.subtotal_amount = Calculator.amount(
                item.quantity,
                item.purchase_price
            );

            item.vat_rate = State.purchase.vat_rate;

            item.vat_amount = Calculator.vat(
                item.subtotal_amount,
                item.vat_rate
            );

            item.total_amount = item.subtotal_amount;

            item.total_amount_with_vat = Calculator.total(
                item.total_amount,
                item.vat_amount
            );

            subtotalAmount += item.subtotal_amount;
            vatAmount += item.vat_amount;
            totalAmount += item.total_amount_with_vat;

        }

        State.purchase.subtotal_amount = subtotalAmount;
        State.purchase.vat_amount = vatAmount;
        State.purchase.total_amount = totalAmount;

        State.purchase.debt_amount = Calculator.debt(
            totalAmount,
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

        return await Api.createPurchase(
            State.purchase
        );

    }

};

export default Service;