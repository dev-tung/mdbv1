const State = {

	filters: {

		keyword: '',

		date_from: '',

		date_to: '',

		status: '',

		category_id: '',

	},



	products: [],



	categories: {},



	summary: {

		total: 0,

	},



	pagination: {

		page: 1,

		per_page: 10,

		total: 0,

		last_page: 1,

	},



	// =========================
	// CATEGORY
	// =========================

	setCategories(categories = []) {

		return Object.fromEntries(

			categories.map(({ id, name }) => [

				id,

				{
					label: name,
				},

			])

		);

	},



	// =========================
	// DEFAULT
	// =========================

	setDefault(data) {


		this.products = data.products || [];



		this.categories = this.setCategories(

			data.categories || []

		);



		this.pagination = {

			...this.pagination,

			...(data.pagination || {}),

		};



		this.setSummary();


	},



	// =========================
	// SUMMARY
	// =========================

	setSummary() {


		this.summary = {

			total: this.pagination.total || this.products.length,

		};


	},


};


export default State;