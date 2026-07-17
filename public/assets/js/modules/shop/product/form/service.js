import Api from './api.js';

const Service = {
	/* =================================================
	   OPTIONS
	================================================= */

	async getOptions() {
		const [categories, brands] = await Promise.all([
			Api.getCategories(),
			Api.getBrands(),
		]);

		return {
			categories: categories.data || [],
			brands: brands.data || [],
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
	   CREATE
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
		};
	},

	/* =================================================
	   UPDATE
	================================================= */

	updatePayload(id, product = {}) {
		return {
			id,
			...this.payload(product),
		};
	},
};

export default Service;