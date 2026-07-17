import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   PRODUCT
	================================================= */

	async getProducts(params = {}) {
		return await Http.get('/api/products', params);
	},

	async updateProductStatus(id, status) {
		return await Http.post('/api/products/status', {
			id,

			status,
		});
	},

	async deleteProduct(id) {
		return await Http.post(`/api/products/delete/${id}`);
	},

	/* =================================================
	   CATEGORY
	================================================= */

	async getCategories(keyword = '') {
		return await Http.get('/api/categories', {
			keyword,
		});
	},
};

export default Api;
