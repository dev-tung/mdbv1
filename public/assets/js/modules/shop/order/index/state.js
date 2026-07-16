const State = {
	filters: {
		date_from: '',
		date_to: '',
		customer: '',
		payment: '',
	},

	orders: [],

	summary: {
		total_amount: 0,
		paid_amount: 0,
		debt_amount: 0,
	},

	pagination: {
		page: 1,
		limit: 20,
		total: 0,
		total_pages: 1,
	},

	setDefault(data) {
		this.orders = data.orders || [];

		this.pagination = {
			...this.pagination,
			...(data.pagination || {}),
		};

		this.setSummary();
	},

	setSummary() {
		const total_amount = this.orders.reduce(
			(sum, order) => sum + Number(order.total_amount || 0),
			0,
		);

		const paid_amount = this.orders.reduce(
			(sum, order) => sum + Number(order.paid_amount || 0),
			0,
		);

		const debt_amount = this.orders.reduce(
			(sum, order) => sum + Number(order.debt_amount || 0),
			0,
		);

		this.summary = {
			total_amount,
			paid_amount,
			debt_amount,
		};
	},
};

export default State;
