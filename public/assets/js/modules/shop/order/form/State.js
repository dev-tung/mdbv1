const State = {
	/* =================================================
       CUSTOMER SEARCH
    ================================================= */

	customer: {
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
       ORDER
    ================================================= */

	order: {
		id: null,

		customer_id: null,

		customer_name: '',

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


                selling_price: 1300000,


                subtotal_amount: 1300000,


                vat_rate: 8,


                vat_amount: 104000,


                total_amount: 1404000
            }
            */
		],
	},

	/* =================================================
       RESET
    ================================================= */

	reset() {
		this.customer = {
			keyword: '',

			suggestions: [],
		};

		this.product = {
			keyword: '',

			suggestions: [],
		};

		this.order = {
			id: null,

			customer_id: null,

			customer_name: '',

			description: '',

			note: '',

			status: 'pending',

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
