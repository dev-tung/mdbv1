import Api from '/assets/js/helpers/api.js';

const Api = {
	/* =================================================
       PURCHASES
    ================================================= */

	getPurchases(params = {}) {
		return Api.get('/api/purchases', params);
	},

	getSuppliers() {
		return Api.get('/api/suppliers');
	},

	/* =================================================
       UPDATE
    ================================================= */

	updateStatus(id, status) {
		return Api.post('/api/purchases/status', {
			id,
			status,
		});
	},

	updatePayment(id, payment) {
		return Api.post('/api/purchases/payment', {
			id,
			payment,
		});
	},

	/* =================================================
       DELETE
    ================================================= */

	delete(id) {
		return Api.post(`/api/purchases/delete/${id}`);
	},
};

export default Api;
