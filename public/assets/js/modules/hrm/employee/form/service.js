import Api from './api.js';

const Service = {
	/* =================================================
	   SHOW
	================================================= */

	async getEmployee(id) {
		const response = await Api.getEmployee(id);

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
