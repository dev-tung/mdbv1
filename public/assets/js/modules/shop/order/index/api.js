import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   ORDER
	================================================= */

	async getOrders(params = {}) {
		return await Http.get('/api/shop/orders', params);
	},

	async updateOrderStatus(id, status) {
		return await Http.post('/api/shop/orders/status', {
			id,
			status,
		});
	},

	async updateOrderPayment(id, payment) {
		return await Http.post('/api/shop/orders/payment', {
			id,
			payment,
		});
	},

	async deleteOrder(id) {
		return await Http.post(`/api/shop/orders/delete/${id}`);
	},
};

export default Api;
