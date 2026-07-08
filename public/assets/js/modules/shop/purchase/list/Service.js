import State from './State.js';
import Api from './Api.js';
import Renderer from './Renderer.js';

const Service = {
    /* =================================================
       LOAD
    ================================================= */

    async loadPurchases() {
        const response = await Api.getPurchases(State.filter);

        State.purchases = response.data;

        State.summary = {
            total_amount: State.purchases.reduce((sum, item) => sum + Number(item.total_amount || 0), 0),
            paid_amount: State.purchases.reduce((sum, item) => sum + Number(item.paid_amount || 0), 0),
            debt_amount: State.purchases.reduce((sum, item) => sum + Number(item.debt_amount || 0), 0),
        };

        State.pagination = {
            current_page: 1,
            last_page: 1,
            per_page: State.purchases.length,
            total: State.purchases.length,
        };

        Renderer.render();
    },

    async loadSuppliers() {
        const response = await Api.getSuppliers();

        State.suppliers = response.data || [];

        Renderer.renderSuppliers();
    },

    /* =================================================
       ACTION
    ================================================= */

    async status(id, status) {
        const response = await Api.status(id, status);

        await this.loadPurchases();
    },

    async payment(id, payment) {
        const response = await Api.payment(id, payment);

        await this.loadPurchases();
    },

    async deletePurchase(id) {
        const response = await Api.deletePurchase(id);

        await this.loadPurchases();
    },
};

export default Service;
