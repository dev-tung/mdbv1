import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   SUPPLIER
	================================================= */

	createSupplier(data) {
		return Http.post('/api/shop/suppliers', data);
	},

	updateSupplier(data) {
		return Http.post(`/api/shop/suppliers/update/${data.id}`, data);
	},

	getSupplier(id) {
		return Http.get(`/api/shop/suppliers/show/${id}`);
	},
};

export default Api;
