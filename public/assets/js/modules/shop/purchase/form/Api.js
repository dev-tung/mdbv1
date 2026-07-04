import Http from '/assets/js/common/Http.js';

const Api = {

    async getWarehouses() {
        return await Http.get('/api/warehouses');
    },

    async showPurchase(purchaseId) {
        return await Http.get(`/api/purchases/show/${purchaseId}`);
    },

    async createPurchase(data) {
        return await Http.post('/api/purchases', data);
    },

    async updatePurchase(purchaseId, data) {
        return await Http.post(`/api/purchase/${purchaseId}`, data);
    },

    async deletePurchase(purchaseId) {
        return await Http.post(`/api/purchase/${purchaseId}`);
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