import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
       PURCHASE
    ================================================= */

	async showPurchase(id) {
		return await Http.get(`/api/shop/purchases/show/${id}`);
	},

	async createPurchase(data) {
		return await Http.post('/api/shop/purchases', data);
	},

	async updatePurchase(id, data) {
		return await Http.post(`/api/shop/purchases/update/${id}`, data);
	},

	async deletePurchase(id) {
		return await Http.post(`/api/shop/purchases/delete/${id}`);
	},

	/* =================================================
       PRODUCT
    ================================================= */

	async searchProduct(keyword = '') {
		return await Http.get('/api/shop/products', {
			keyword,
		});
	},

	/* =================================================
       SUPPLIER
    ================================================= */

	async searchSupplier(keyword = '') {
		return await Http.get('/api/shop/suppliers', {
			keyword,
		});
	},

	/* =================================================
       WAREHOUSE
    ================================================= */

	async getWarehouses() {
		return await Http.get('/api/shop/warehouses');
	},
};

export default Api;
