import State from './state.js';
import Api from './api.js';

const Service = {

    /* =================================================
       SUPPLIER
    ================================================= */

    async searchSupplier(keyword) {

        return await Api.searchSupplier(keyword);

    },

    selectSupplier(supplier) {

        State.purchase.supplier_id = supplier.id;
        State.purchase.supplier_name = supplier.name;

    },

    /* =================================================
       PRODUCT
    ================================================= */

    async searchProduct(keyword) {

        return await Api.searchProduct(keyword);

    },

    addProduct(product) {

        const item = State.items.find(
            item => item.product_id === product.id
        );

        if (item) {

            item.quantity++;

            return;

        }

        State.items.push({

            product_id: product.id,
            code: product.code,
            name: product.name,

            quantity: 1,

            purchase_price: product.purchase_price ?? 0,
            selling_price: product.selling_price ?? 0

        });

    },

    /* =================================================
       ITEMS
    ================================================= */

    changeItem(e) {

        const row = e.target.closest('tr');

        if (!row) {
            return;
        }

        const index = Number(row.dataset.index);

        const item = State.items[index];

        if (!item) {
            return;
        }

        if (e.target.classList.contains('quantity')) {

            item.quantity = Number(e.target.value);

        }

        if (e.target.classList.contains('purchase-price')) {

            item.purchase_price = Number(e.target.value);

        }

        if (e.target.classList.contains('selling-price')) {

            item.selling_price = Number(e.target.value);

        }

    },

    removeItem(e) {

        const row = e.target.closest('tr');

        if (!row) {
            return;
        }

        const index = Number(row.dataset.index);

        State.items.splice(index, 1);

    },

    /* =================================================
       PURCHASE
    ================================================= */

    changeVat(value) {

        State.purchase.vat_rate = Number(value);

    },

    changePayment(value) {

        State.purchase.paid_amount = Number(value);

    },

    /* =================================================
       SAVE
    ================================================= */

    async save() {

        return await Api.save(State);

    }

};

export default Service;