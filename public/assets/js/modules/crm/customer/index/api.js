import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   CUSTOMER
	================================================= */

	async getCustomers(params = {}) {
		return await Http.get('/api/customers', params);
	},

	async deleteCustomer(id) {
		return await Http.post(`/api/customers/delete/${id}`);
	},
};

export default Api;
