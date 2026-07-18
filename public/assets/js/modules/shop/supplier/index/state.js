const State = {
	/* =================================================
	   FILTERS
	================================================= */

	filters: {
		keyword: '',

		date_from: '',

		date_to: '',
	},

	/* =================================================
	   DATA
	================================================= */

	suppliers: [],

	summary: {
		total: 0,
	},

	pagination: {
		page: 1,

		per_page: 10,

		total: 0,

		last_page: 1,
	},

	/* =================================================
	   DEFAULT
	================================================= */

	setDefault(data) {
		this.suppliers = data.suppliers || [];

		this.summary = {
			...this.summary,

			...(data.summary || {}),
		};

		this.pagination = {
			...this.pagination,

			...(data.pagination || {}),
		};
	},
};

export default State;
