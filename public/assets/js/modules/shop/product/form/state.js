const State = {
	/* =================================================
	   FORM
	================================================= */

	form: {
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
