import Api from './api.js';

const Service = {
	/* =================================================
	   LIST
	================================================= */

	async getList(filters = {}) {
		const [productsResponse, categoriesResponse, brandsResponse] = await Promise.all([
			Api.getProducts(filters),
			Api.getCategories(),
			Api.getBrands(),
		]);

		const [products, [summary]] = productsResponse.data;

		const [categories] = categoriesResponse.data;

		const [brands] = brandsResponse.data;

		const page = Number(filters.page ?? 1);

		const per_page = Number(filters.per_page ?? 20);

		const total = Number(summary.total);

		return {
			products,

			categories,

			brands,

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
