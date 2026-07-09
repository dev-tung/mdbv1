const State = {
	/* =================================================
       SUPPLIER SEARCH
    ================================================= */

	supplier: {
		keyword: '',
		suggestions: [],
	},

	/* =================================================
       PRODUCT SEARCH
    ================================================= */

	product: {
		keyword: '',
		suggestions: [],
	},

	/* =================================================
       WAREHOUSE
    ================================================= */

	warehouse: {
		list: [],
	},

	/* =================================================
       PURCHASE
    ================================================= */

	purchase: {
		id: null,

		supplier_id: null,
		supplier_name: '',

		warehouse_id: null,

		description: '',
		note: '',

		status: 'draft',
		payment: 'unpaid',

		subtotal_amount: 0,

		vat_rate: 0,
		vat_amount: 0,

		total_amount: 0,

		paid_amount: 0,
		debt_amount: 0,

		items: [
			/*
            {
                product_id: 1,
                product_name: 'Yonex 88D Pro',

                quantity: 1,

                purchase_price: 1000000,
                selling_price: 1300000,

                subtotal_amount: 1000000,

                vat_rate: 8,
                vat_amount: 80000,

                total_amount: 1000000,
                total_amount_with_vat: 1080000
            }
            */
		],
	},

	/* =================================================
       RESET
    ================================================= */

	reset() {
		this.supplier = {
			keyword: '',
			suggestions: [],
		};

		this.product = {
			keyword: '',
			suggestions: [],
		};

		this.warehouse = {
			list: [],
		};

		this.purchase = {
			id: null,

			supplier_id: null,
			supplier_name: '',

			warehouse_id: null,

			description: '',
			note: '',

			status: 'draft',
			payment: 'unpaid',

			subtotal_amount: 0,

			vat_rate: 0,
			vat_amount: 0,

			total_amount: 0,

			paid_amount: 0,
			debt_amount: 0,

			items: [],
		};
	},
};

State.reset();

export default State;
