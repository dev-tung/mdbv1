import State from './State.js';
import Api from './Api.js';
import Renderer from './Renderer.js';

/* =================================================
   SERVICE OBJECT
================================================= */

const Service = {

    /* =================================================
       LOAD DATA
    ================================================= */

    async loadSuppliers() {
        State.suppliers = await Api.getSuppliers();
    },

    async loadWarehouses() {
        const response = await Api.getWarehouses();
        State.warehouse.list = response.data || [];
    },

    async loadProducts() {
        State.products = await Api.getProducts();
    },

    async loadEditData(purchaseId) {

        const data = await Api.getPurchase(purchaseId);

        State.purchase = data;

        State.purchase.items = State.purchase.items || [];

        this.recalc();
    },

    /* =================================================
       UPDATE HEADER
    ================================================= */

    setWarehouse(warehouseId) {
        State.purchase.warehouseId = warehouseId;
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

        const { items, paidAmount } = State.purchase;

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
            totalAmount - (Number(paidAmount) || 0);

        Renderer.summary();
    },

    /* =================================================
       SAVE
    ================================================= */

    async save() {

        // tạm bỏ validate
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