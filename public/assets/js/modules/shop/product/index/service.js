import Api from './api.js';

const Service = {
	/* =================================================
		LIST
	================================================= */

	async getList(filters = {}) {
		const [productsResponse, categoriesResponse] = await Promise.all([
			Api.getProducts(filters),
			Api.getCategories(),
		]);

		const [products, [summary]] = productsResponse.data;

		const [categories] = categoriesResponse.data;

		const total = Number(summary.total);

		const per_page = Number(filters.per_page ?? 10);

		return {
			products,

			categories,

			summary,

			pagination: {
				page: Number(filters.page ?? 1),

				per_page,

				total,

				last_page: Math.ceil(total / per_page),
			},
		};
	},
};

export default Service;
