import Api from './api.js';

const Service = {
	/* =================================================
	   LIST
	================================================= */

	async getList(filters = {}) {
		const [productsResponse, categoriesResponse, brandsResponse] =
			await Promise.all([
				Api.getProducts(filters),
				Api.getCategories(),
				Api.getBrands(),
			]);

		const productsData = productsResponse?.data ?? [];
		const categoriesData = categoriesResponse?.data ?? [];
		const brandsData = brandsResponse?.data ?? [];

		const products = productsData[0] ?? [];

		const summary = productsData[1]?.[0] ?? {
			total: 0,
			total_price: 0,
			total_sale_price: 0,
		};

		const categories = categoriesData[0] ?? [];

		const brands = brandsData[0] ?? [];

		const page = Number(filters.page ?? 1);

		const per_page = Number(filters.per_page ?? 20);

		const total = Number(summary.total ?? 0);

		return {
			products,

			categories,

			brands,

			summary,

			pagination: {
				page,

				per_page,

				total,

				last_page: Math.max(1, Math.ceil(total / per_page)),
			},
		};
	},
};

export default Service;