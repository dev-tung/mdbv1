import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   PRODUCT
	================================================= */

	createProduct(data) {
		return Http.post('/api/products', data);
	},

	updateProduct(data) {
		return Http.post(`/api/products/update/${data.id}`, data);
	},

	getProduct(id) {
		return Http.get(`/api/products/show/${id}`);
	},

	/* =================================================
	   CATEGORY
	================================================= */

	getCategories() {
		return Http.get('/api/categories');
	},

	/* =================================================
	   BRAND
	================================================= */

	getBrands() {
		return Http.get('/api/brands');
	},
};

export default Api;