import Api from './api.js';

const Service = {
	async getList(filters = {}) {
		const [productResponse, categoryResponse] = await Promise.all([
			Api.getProducts(filters),
			Api.getCategories(),
		]);

		const data = Array.isArray(productResponse?.data)
			? productResponse.data
			: [];

		const products = data[0] ?? [];

		const summary = data[1] ?? {};

		const page = Number(filters.page ?? 1);

		const per_page = Number(filters.per_page ?? 10);

		const total = Number(summary.total ?? 0);

		return {
			products,

			categories: categoryResponse.data ?? [],

			summary,

			pagination: {
				page,

				per_page,

				total,

				last_page: Math.ceil(total / per_page),
			},
		};
	},
};

export default Service;