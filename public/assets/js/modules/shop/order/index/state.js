const State = {
	/* =================================================
	   FILTERS
	================================================= */

	filters: {
		date_from: '',
		date_to: '',
		customer: '',
		payment: '',
	},

	/* =================================================
	   DATA
	================================================= */

	orders: [],

	summary: {
		total_amount: 0,
		paid_amount: 0,
		debt_amount: 0,
	},

	pagination: {
		page: 1,

		per_page: 20,

		total: 0,

		last_page: 1,
	},

	/* =================================================
	   DEFAULT
	================================================= */

	setDefault(data) {
		this.orders = data.orders || [];

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