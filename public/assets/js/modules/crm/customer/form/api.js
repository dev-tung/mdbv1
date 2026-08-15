import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   CUSTOMER
	================================================= */

	createCustomer(data) {
		return Http.post('/api/customers', data);
	},

	updateCustomer(data) {
		return Http.post(`/api/customers/update/${data.id}`, data);
	},

	getCustomer(id) {
		return Http.get(`/api/customers/show/${id}`);
	},

	/* =================================================
	   CUSTOMER GROUP
	================================================= */

	getCustomerGroups() {
		return Http.get('/api/customer-groups');
	},

	createCustomerGroup(data) {
		return Http.post('/api/customer-groups', data);
	},
};

export default Api;