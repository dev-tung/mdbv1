import State from './state.js';

import Dom from '../../../../helpers/dom.js';

const Renderer = {
	/* =================================================
	   PUBLIC
	================================================= */

	render() {
		this.renderSupplier();
	},

	/* =================================================
	   SUPPLIER
	================================================= */

	renderSupplier() {
		const supplier = State.form;

		Dom.value('#name', supplier.name);

		Dom.value('#phone', supplier.phone);

		Dom.value('#email', supplier.email);

		Dom.value('#address', supplier.address);

		Dom.value('#description', supplier.description);
	},
};

export default Renderer;
