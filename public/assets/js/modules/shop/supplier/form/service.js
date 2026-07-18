import Api from './api.js';

const Service = {
	/* =================================================
	   SHOW
	================================================= */

	async getSupplier(id) {
		const response = await Api.getSupplier(id);

		return response.data;
	},

	/* =================================================
	   PAYLOAD
	================================================= */

	payload(form) {
		return {
			name: form.name,

			phone: form.phone,

			email: form.email,

			address: form.address,

			description: form.description,
		};
	},

	/* =================================================
	   UPDATE PAYLOAD
	================================================= */

	updatePayload(id, form) {
		return {
			id,

			...this.payload(form),
		};
	},
};

export default Service;
