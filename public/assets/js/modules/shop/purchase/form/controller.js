import Event from './event.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Controller = {

    /* =================================================
       PUBLIC
    ================================================= */

    async init() {

        try {

            Renderer.render();

            Event.bind();

        } catch (error) {

            console.error(error);

        }

    },

    /* =================================================
       SUPPLIER
    ================================================= */

    selectSupplier(item) {

        Service.selectSupplier(item);

        Renderer.renderPurchase();

    },

    /* =================================================
       PRODUCT
    ================================================= */

    selectProduct(item) {

        Service.addProduct(item);

        Renderer.renderProducts();

    },

    /* =================================================
       ITEMS
    ================================================= */

    changeItem(e) {

        Service.changeItem(e);

        Renderer.renderProducts();

    },

    removeItem(e) {

        Service.removeItem(e);

        Renderer.renderProducts();

    },

    /* =================================================
       PURCHASE
    ================================================= */

    changeVat(e) {

        Service.changeVat(e.target.value);

        Renderer.renderProducts();

    },

    changePayment(e) {

        Service.changePayment(e.target.value);

        Renderer.renderSummary();

    },

    /* =================================================
       SUBMIT
    ================================================= */

    async submit(e) {

        e.preventDefault();

        await Service.save();

    }

};

export default Controller;