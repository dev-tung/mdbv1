import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
       PURCHASE
    ================================================= */

	async getPurchases(params = {}) {
		return await Http.get('/api/purchases', params);
	},

	async updatePurchaseStatus(id, status) {
		return await Http.post('/api/purchases/status', {
			id,
			status,
		});
	},

	async updatePurchasePayment(id, payment) {
		return await Http.post('/api/purchases/payment', {
			id,
			payment,
		});
	},

	/* =================================================
       SUPPLIER
    ================================================= */

	async getSuppliers(keyword = '') {
		return await Http.get('/api/suppliers', {
			keyword,
		});
	},
};

export default Api;
