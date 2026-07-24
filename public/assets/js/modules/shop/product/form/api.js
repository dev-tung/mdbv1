import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   PRODUCT
	================================================= */

	createProduct(data) {
		return Http.post('/api/shop/products', data);
	},

	updateProduct(data) {
		return Http.post(`/api/shop/products/update/${data.id}`, data);
	},

	getProduct(id) {
		return Http.get(`/api/shop/products/show/${id}`);
	},

	/* =================================================
	   CATEGORY
	================================================= */

	getCategories() {
		return Http.get('/api/shop/categories');
	},

	/* =================================================
	   BRAND
	================================================= */

	getBrands() {
		return Http.get('/api/shop/brands');
	},
};

export default Api;
