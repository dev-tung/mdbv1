const State = {
	purchases: [],

	suppliers: [],

	options: window.PurchaseConfig.options,

	filter: {
		date_from: '',
		date_to: '',
		supplier_id: '',
		payment: '',
		page: 1,
		per_page: 20,
	},

	summary: {
		total_amount: 0,
		paid_amount: 0,
		debt_amount: 0,
	},

	pagination: {
		current_page: 1,
		last_page: 1,
		per_page: 20,
		total: 0,
	},
};

export default State;
