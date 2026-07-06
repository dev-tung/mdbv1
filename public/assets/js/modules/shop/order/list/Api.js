import Http from '/assets/js/common/Http.js';

const Api = {

    /* =================================================
       ORDERS
    ================================================= */

    async getOrders(params = {}) {

        return await Http.get('/api/orders', params);

    },

    async getSuppliers() {

        return await Http.get('/api/suppliers');

    },

    /* =================================================
       ACTIONS
    ================================================= */

    async updateStatus(id, status) {

        return await Http.post('/api/orders/status', {
            id,
            status
        });

    },

    async payment(id, payment) {

        return await Http.post('/api/orders/payment', {
            id,
            payment
        });

    },

    async deleteOrder(id) {

        return await Http.delete(`/api/orders/${id}`);

    }

};

export default Api;