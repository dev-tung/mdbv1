import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
       PURCHASE
    ================================================= */

	async getPurchases(params = {}) {
		return await Http.get('/api/shop/purchases', params);
	},

	async updatePurchaseStatus(id, status) {
		return await Http.post('/api/shop/purchases/status', {
			id,
			status,
		});
	},

	async updatePurchasePayment(id, payment) {
		return await Http.post('/api/shop/purchases/payment', {
			id,
			payment,
		});
	},

	async deletePurchase(id) {
		return await Http.post(`/api/shop/purchases/delete/${id}`);
	},

	/* =================================================
       SUPPLIER
    ================================================= */

	async getSuppliers(keyword = '') {
		return await Http.get('/api/shop/suppliers', {
			keyword,
		});
	},
};

export default Api;
