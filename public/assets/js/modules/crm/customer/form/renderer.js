import State from './state.js';

import Dom from '../../../../helpers/dom.js';

const Renderer = {
	/* =================================================
	   PUBLIC
	================================================= */

	render() {
		this.renderCustomer();
	},

	/* =================================================
	   CUSTOMER
	================================================= */

	renderCustomer() {
		const customer = State.form;

		Dom.value('#name', customer.name);

		Dom.value('#phone', customer.phone);

		Dom.value('#email', customer.email);

		Dom.value('#address', customer.address);

		Dom.value('#description', customer.description);
	},
};

export default Renderer;
