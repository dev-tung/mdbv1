const State = {
	filters: {
		keyword: '',

		category_id: '1',

		brand_id: '',

		price: '',

		status: '',
	},

	products: [],

	categories: {},

	brands: {},

	pagination: {
		page: 1,

		per_page: 20,

		total: 0,

		last_page: 1,
	},

	/* =================================================
	   CATEGORY
	================================================= */

	setCategories(categories = []) {
		return Object.fromEntries(
			categories.map(({ id, name }) => [
				id,
				{
					label: name,
				},
			]),
		);
	},

	/* =================================================
	   BRAND
	================================================= */

	setBrands(brands = []) {
		return Object.fromEntries(
			brands.map(({ id, name }) => [
				id,
				{
					label: name,
				},
			]),
		);
	},

	/* =================================================
	   DEFAULT
	================================================= */

	setDefault(data) {
		this.products = data.products || [];

		this.categories = this.setCategories(data.categories || []);

		this.brands = this.setBrands(data.brands || []);

		this.pagination = {
			...this.pagination,

			...(data.pagination || {}),
		};
	},
};

export default State;
