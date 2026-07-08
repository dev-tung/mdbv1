import Http from '/assets/js/common/Http.js';

const Api = {
    /* =================================================
       ORDERS
    ================================================= */

    async getOrders(params = {}) {
        return await Http.get('/api/orders', params);
    },

    async getCustomers() {
        return await Http.get('/api/customers');
    },

    /* =================================================
       ACTIONS
    ================================================= */

    async status(id, status) {
        return await Http.post('/api/orders/status', {
            id,
            status,
        });
    },

    async payment(id, payment) {
        return await Http.post('/api/orders/payment', {
            id,
            payment,
        });
    },

    async deleteOrder(id) {
        return await Http.post(`/api/orders/delete/${id}`);
    },
};

export default Api;
