import State from './state.js';

import Dom from '../../../../helpers/dom.js';

const Renderer = {
	/* =================================================
	   PUBLIC
	================================================= */

	render() {
		this.renderEmployee();
	},

	/* =================================================
	   EMPLOYEE
	================================================= */

	renderEmployee() {
		const employee = State.form;

		Dom.value('#name', employee.name);

		Dom.value('#phone', employee.phone);

		Dom.value('#email', employee.email);

		Dom.value('#address', employee.address);

		Dom.value('#description', employee.description);
	},
};

export default Renderer;
