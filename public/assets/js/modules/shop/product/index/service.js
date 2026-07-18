import Api from './api.js';

const Service = {
	/* =================================================
	   LIST
	================================================= */

	async getList(filters = {}) {
		const productsResponse = await Api.getProducts(filters);

		const categoriesResponse = await Api.getCategories();

		const products = productsResponse.data[0];

		const summary = productsResponse.data[1][0];

		const total = Number(summary.total);

		const per_page = Number(filters.per_page ?? 10);

		return {
			products,

			categories: categoriesResponse.data,

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