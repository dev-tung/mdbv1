import Http from '/assets/js/common/Http.js';

const Api = {
    /* =================================================
       PURCHASE
    ================================================= */

    async showPurchase(id) {
        return await Http.get(`/api/purchases/show/${id}`);
    },

    async createPurchase(data) {
        return await Http.post('/api/purchases', data);
    },

    async updatePurchase(id, data) {
        return await Http.post(`/api/purchases/update/${id}`, data);
    },

    async deletePurchase(id) {
        return await Http.post(`/api/purchases/delete/${id}`);
    },

    /* =================================================
       PRODUCT
    ================================================= */

    async searchProducts(keyword = '') {
        return await Http.get('/api/products', {
            keyword,
        });
    },

    /* =================================================
       SUPPLIER
    ================================================= */

    async searchSuppliers(keyword = '') {
        return await Http.get('/api/suppliers', {
            keyword,
        });
    },

    /* =================================================
       WAREHOUSE
    ================================================= */

    async getWarehouses() {
        return await Http.get('/api/warehouses');
    },
};

export default Api;
