const State = {
	/* =================================================
	   DEFAULT
	================================================= */

	defaultForm() {
		return {
			id: null,

			name: '',

			price: '',

			sale_price: '',

			category_id: '',

			brand_id: '',

			status: 'active',

			description: '',

			thumbnail: null,
		};
	},

	/* =================================================
	   FORM
	================================================= */

	form: {},

	/* =================================================
	   OPTIONS
	================================================= */

	options: {
		categories: [],

		brands: [],
	},

	/* =================================================
	   STATUS
	================================================= */

	loading: false,

	submitting: false,

	/* =================================================
	   SET OPTIONS
	================================================= */

	setOptions(data = {}) {
		this.options = {
			categories: data.categories || [],

			brands: data.brands || [],
		};
	},

	/* =================================================
	   SET PRODUCT
	================================================= */

	setProduct(product = {}) {
		this.form = {
			...this.form,

			...product,
		};
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
	},
};

State.reset();

export default State;
