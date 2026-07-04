import State from './State.js';
import Api from './Api.js';
import Renderer from './Renderer.js';

/* =================================================
   SERVICE OBJECT
================================================= */

const Service = {

    async loadPurchase(id) {
        const response = await Api.getPurchase(id);
        State.purchase = response.data;
    },

    async loadSuppliers() {
        const response = await Api.getSuppliers();
        State.suppliers = response.data || [];
    },

    async loadWarehouses() {
        const response = await Api.getWarehouses();
        State.warehouse.list = response.data || [];
    },

    async loadProducts() {
        const response = await Api.getProducts();
        State.products = response.data || [];
    },

    /* =================================================
       ITEMS CRUD
    ================================================= */

    setProduct(index, productId) {
        State.purchase.items[index].productId = productId;
        this.recalc();
    },

    setQuantity(index, quantity) {
        State.purchase.items[index].quantity = Number(quantity) || 0;
        this.recalc();
    },

    setPrice(index, price) {
        State.purchase.items[index].purchasePrice = Number(price) || 0;
        this.recalc();
    },

    addItem() {
        State.addItem();
        this.recalc();
    },

    removeItem(index) {
        State.purchase.items.splice(index, 1);
        this.recalc();
    },

    /* =================================================
       CALCULATION (tạm thời inline logic)
    ================================================= */

    recalc() {

        const { items, paid_amount } = State.purchase;

        // totalAmount
        let totalAmount = 0;

        for (const item of items) {
            const qty = Number(item.quantity) || 0;
            const price = Number(item.purchasePrice) || 0;
            totalAmount += qty * price;
        }

        State.purchase.totalAmount = totalAmount;

        // debtAmount
        State.purchase.debtAmount =
            totalAmount - (Number(paid_amount) || 0);
    },

    /* =================================================
       SAVE
    ================================================= */

    async save() {
        this.recalc();

        if (State.purchase.id) {
            await Api.updatePurchase(State.purchase.id, State.purchase);
        } else {
            await Api.createPurchase(State.purchase);
        }

        return { success: true };
    }
};

export default Service;