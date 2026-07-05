import Http from '/assets/js/common/Http.js';

const Api = {

    /* =================================================
       PURCHASES
    ================================================= */

    async getPurchases(params = {}) {

        return await Http.get('/api/purchases', params);

    },

    async getSuppliers() {

        return await Http.get('/api/suppliers');

    },

    /* =================================================
       ACTIONS
    ================================================= */

    async updateStatus(id, status) {

        return await Http.post('/api/purchases/status', {
            id,
            status
        });

    },

    async payment(id, payment) {

        return await Http.post('/api/purchases/payment', {
            id,
            payment
        });

    },

    async deletePurchase(id) {

        return await Http.delete(`/api/purchases/${id}`);

    }

};

export default Api;