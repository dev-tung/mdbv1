const State = {
	/* =================================================
	   FORM
	================================================= */

	form: {
		id: null,

		name: '',

		price: '',

		sale_price: '',

		category_id: '',

		brand_id: '',

		status: 'active',

		description: '',

		thumbnail: null,
	},

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
		this.form = {
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
};

export default State;