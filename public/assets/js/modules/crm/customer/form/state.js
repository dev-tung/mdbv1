const State = {
	/* =================================================
	   DEFAULT
	================================================= */

	defaultForm() {
		return {
			id: null,

			name: '',

			group_id: null,

			phone: '',

			email: '',

			address: '',

			description: '',
		};
	},

	/* =================================================
	   FORM
	================================================= */

	form: {},

	/* =================================================
	   CUSTOMER GROUP
	================================================= */

	customerGroup: [],

	/* =================================================
	   STATUS
	================================================= */

	loading: false,

	submitting: false,

	/* =================================================
	   SET CUSTOMER
	================================================= */

	setCustomer(customer = {}) {
		this.form = {
			...this.form,

			...customer,
		};
	},

	/* =================================================
	   SET CUSTOMER GROUP
	================================================= */

	setCustomerGroup(customerGroup = []) {
		this.customerGroup = customerGroup;
	},

	/* =================================================
	   SET FIELD
	================================================= */

	setField(key, value) {
		if (key in this.form) {
			this.form[key] = value;
		}
	},

	/* =================================================
	   RESET
	================================================= */

	reset() {
		this.form = this.defaultForm();

		this.customerGroup = [];
	},
};

State.reset();

export default State;