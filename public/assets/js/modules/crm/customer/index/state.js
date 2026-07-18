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

	customers: [],

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
		this.customers = data.customers || [];

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
