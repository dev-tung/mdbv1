import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   CUSTOMER
	================================================= */

	createCustomer(data) {
		return Http.post('/api/crm/customers', data);
	},

	updateCustomer(data) {
		return Http.post(`/api/crm/customers/update/${data.id}`, data);
	},

	getCustomer(id) {
		return Http.get(`/api/crm/customers/show/${id}`);
	},
};

export default Api;
