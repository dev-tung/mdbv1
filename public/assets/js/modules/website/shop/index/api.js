import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   PRODUCT
	================================================= */

	getProducts(params = {}) {
		return Http.get('/api/shop/products', params);
	},

	/* =================================================
	   CATEGORY
	================================================= */

	getCategories(params = {}) {
		return Http.get('/api/shop/categories', params);
	},

	/* =================================================
	   BRAND
	================================================= */

	getBrands(params = {}) {
		return Http.get('/api/shop/brands', params);
	},
};

export default Api;
