import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
       PURCHASE
    ================================================= */

	async getPurchases(params = {}) {
		return await Http.get('/api/purchases', params);
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