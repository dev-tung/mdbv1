import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   PRODUCT
	================================================= */

	getProducts(params = {}) {
		return Http.get('/api/products', params);
	},

	/* =================================================
	   CATEGORY
	================================================= */

	getCategories(params = {}) {
		return Http.get('/api/categories', params);
	},

	/* =================================================
	   BRAND
	================================================= */

	getBrands(params = {}) {
		return Http.get('/api/brands', params);
	},
};

export default Api;