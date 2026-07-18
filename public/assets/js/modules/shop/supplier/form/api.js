import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   SUPPLIER
	================================================= */

	createSupplier(data) {
		return Http.post('/api/suppliers', data);
	},

	updateSupplier(data) {
		return Http.post(`/api/suppliers/update/${data.id}`, data);
	},

	getSupplier(id) {
		return Http.get(`/api/suppliers/show/${id}`);
	},
};

export default Api;
