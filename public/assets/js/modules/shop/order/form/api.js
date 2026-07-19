import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   ORDER
	================================================= */

	async showOrder(id) {
		return await Http.get(`/api/orders/show/${id}`);
	},

	async createOrder(data) {
		return await Http.post('/api/orders', data);
	},

	async updateOrder(id, data) {
		return await Http.post(`/api/orders/update/${id}`, data);
	},

	async deleteOrder(id) {
		return await Http.post(`/api/orders/delete/${id}`);
	},

	/* =================================================
	   PRODUCT
	================================================= */

	async searchProduct(keyword = '') {
		return await Http.get('/api/inventories/stock', {
			keyword,
		});
	},

	async getQuantity(product_id, purchase_id) {
		return await Http.get('/api/inventories/quantity', {
			product_id,
			purchase_id,
		});
	},

	/* =================================================
	   CUSTOMER
	================================================= */

	async searchCustomer(keyword = '') {
		return await Http.get('/api/customers', {
			keyword,
		});
	},
};

export default Api;