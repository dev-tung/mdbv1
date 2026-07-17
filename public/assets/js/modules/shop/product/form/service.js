import Api from './api.js';

const Service = {
	/* =================================================
	   OPTIONS
	================================================= */

	async getOptions() {
		const [categoryResponse, brandResponse] = await Promise.all([
			Api.getCategories(),
			Api.getBrands(),
		]);

		return {
			categories: categoryResponse.data ?? [],

			brands: brandResponse.data ?? [],
		};
	},

	/* =================================================
	   PRODUCT
	================================================= */

	async getProduct(id) {
		const response = await Api.getProduct(id);

		return response.data ?? {};
	},

	/* =================================================
	   PAYLOAD
	================================================= */

	payload(product = {}) {
		return {
			name: product.name ?? '',

			price: product.price ?? 0,

			sale_price: product.sale_price ?? 0,

			category_id: product.category_id ?? null,

			brand_id: product.brand_id ?? null,

			status: product.status ?? 'active',

			description: product.description ?? '',

			thumbnail: product.thumbnail ?? null,
		};
	},

	/* =================================================
	   UPDATE PAYLOAD
	================================================= */

	updatePayload(id, product = {}) {
		return {
			id,

			...this.payload(product),
		};
	},
};

export default Service;