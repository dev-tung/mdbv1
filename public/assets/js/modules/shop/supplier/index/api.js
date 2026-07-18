import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   SUPPLIER
	================================================= */

	async getSuppliers(params = {}) {
		return await Http.get('/api/suppliers', params);
	},


	async deleteSupplier(id) {
		return await Http.post(`/api/suppliers/delete/${id}`);
	},
};

export default Api;