import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   ORDER
	================================================= */

	async showOrder(id) {
		return await Http.get(`/api/shop/orders/show/${id}`);
	},

	async createOrder(data) {
		return await Http.post('/api/shop/orders', data);
	},

	async updateOrder(id, data) {
		return await Http.post(`/api/shop/orders/update/${id}`, data);
	},

	async deleteOrder(id) {
		return await Http.post(`/api/shop/orders/delete/${id}`);
	},

	/* =================================================
	   PRODUCT
	================================================= */

	async searchProduct(keyword = '') {
		return await Http.get('/api/shop/report/inventory', {
			keyword,
		});
	},

	async checkQuantity(product_id, purchase_id) {
		return await Http.get('/api/shop/report/inventory', {
			product_id,
			purchase_id,
		});
	},

	/* =================================================
	   CUSTOMER
	================================================= */

	async searchCustomer(keyword = '') {
		return await Http.get('/api/shop/customers', {
			keyword,
		});
	},
};

export default Api;
