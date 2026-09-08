import Api from './api.js';

const Service = {
	/* =================================================
	   CUSTOMER GROUP
	================================================= */

	async getCustomerGroups() {
		const response = await Api.getCustomerGroups();

		return response.data;
	},

	/* =================================================
	   SHOW
	================================================= */

	async getCustomer(id) {
		const response = await Api.getCustomer(id);

		return response.data;
	},

	/* =================================================
	   PAYLOAD
	================================================= */
	payload(form) {
		return {
			name: form.name,

			group_id: form.group_id,

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