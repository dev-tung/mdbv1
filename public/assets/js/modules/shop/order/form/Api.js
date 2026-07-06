import Http from '/assets/js/common/Http.js';

const Api = {

    async showOrder(orderId) {
        return await Http.get(`/api/orders/show/${orderId}`);
    },

    async createOrder(data) {
        return await Http.post('/api/orders', data);
    },

    async updateOrder(orderId, data) {
        return await Http.post(`/api/orders/update/${orderId}`, data);
    },

    async deleteOrder(orderId) {
        return await Http.post(`/api/orders/delete/${orderId}`);
    },

    async searchProducts(keyword = '') {
        return await Http.get('/api/products', {
            keyword
        });
    },

    async searchSuppliers(keyword = '') {
        return await Http.get('/api/suppliers', {
            keyword
        });
    }

};

export default Api;