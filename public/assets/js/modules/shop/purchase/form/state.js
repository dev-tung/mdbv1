const State = {
	/* ===============================
	   PURCHASE
	=============================== */

	purchase: {
		id: null,

		supplier_id: null,

		supplier_name: '',

		warehouse_id: null,

		description: '',

		status: 'draft',

		payment: 'unpaid',

		paid_amount: 0,

		vat_rate: 0,

		items: [],

		subtotal_amount: 0,

		vat_amount: 0,

		total_amount: 0,

		debt_amount: 0,
	},

	/* ===============================
	   SUPPLIER
	=============================== */

	supplier: {
		keyword: '',

		suggestions: [],
	},

	/* ===============================
	   PRODUCT
	=============================== */

	product: {
		keyword: '',

		suggestions: [],
	},

	/* ===============================
	   WAREHOUSE
	=============================== */

	warehouse: {
		list: [],
	},

	/* ===============================
	   UI
	=============================== */

	ui: {
		loading: false,

		mode: 'create',
	},
};

export default State;
