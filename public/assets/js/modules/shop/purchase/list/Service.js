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
            total_amount: State.purchases.reduce(
                (sum, item) => sum + Number(item.total_amount || 0),
                0
            ),
            paid_amount: State.purchases.reduce(
                (sum, item) => sum + Number(item.paid_amount || 0),
                0
            ),
            debt_amount: State.purchases.reduce(
                (sum, item) => sum + Number(item.debt_amount || 0),
                0
            )
        };

        State.pagination = {
            current_page: 1,
            last_page: 1,
            per_page: State.purchases.length,
            total: State.purchases.length
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

    async updateStatus(id, status) {

        const response = await Api.updateStatus(id, status);

        alert(response.message);

        await this.loadPurchases();

    },

    async payment(id, payment) {

        const response = await Api.payment(id, payment);

        alert(response.message);

        await this.loadPurchases();

    },

    async deletePurchase(id) {

        if (!confirm('Bạn có chắc muốn xóa phiếu nhập này?')) {
            return;
        }

        const response = await Api.deletePurchase(id);

        alert(response.message);

        await this.loadPurchases();

    }

};

export default Service;