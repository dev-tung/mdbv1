import State from './state.js';

import Dom from '../../../../helpers/dom.js';

const Renderer = {
	/* =================================================
	   PUBLIC
	================================================= */

	render() {
		this.renderCustomerGroup();

		this.renderCustomer();
	},

	/* =================================================
	   CUSTOMER GROUP
	================================================= */

	renderCustomerGroup() {
		const select = Dom.find('#customer_group_id');

		select.innerHTML = `
			<option value="">-- Chọn nhóm khách hàng --</option>
		`;

		State.customerGroup.forEach((item) => {
			const option = document.createElement('option');

			option.value = item.id;

			option.textContent = item.name;

			select.appendChild(option);
		});

		select.value = State.form.group_id || '';
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