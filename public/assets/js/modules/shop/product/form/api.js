import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   PRODUCT
	================================================= */

	async createProduct(data) {
		return await Http.post('/api/products', data);
	},

	async updateProduct(data) {
		return await Http.post(`/api/products/update/${data.id}`, data);
	},

	async getProduct(id) {
		return await Http.get(`/api/products/show/${id}`);
	},

	/* =================================================
	   CATEGORY
	================================================= */

	async getCategories() {
		return await Http.get('/api/categories');
	},

	/* =================================================
	   BRAND
	================================================= */

	async getBrands() {
		return await Http.get('/api/brands');
	},
};

export default Api;