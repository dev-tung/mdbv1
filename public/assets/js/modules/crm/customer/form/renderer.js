import State from './state.js';

import Dom from '../../../../helpers/dom.js';

const Renderer = {
	/* =================================================
	   PUBLIC
	================================================= */

	render() {
		this.renderCustomerGroup();

		this.renderCustomer();

		this.renderEquipment();
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

	renderEquipment() {
		Dom.find('#customer-rackets').replaceChildren();
		Dom.find('#customer-shoes').replaceChildren();

		(State.form.rackets || []).forEach((racket) => this.addRacket(racket));
		(State.form.shoes || []).forEach((shoe) => this.addShoe(shoe));
	},

	addRacket(racket = {}) {
		const template = Dom.find('#customer-racket-template');
		const card = template.content.firstElementChild.cloneNode(true);

		this.setFields(card, 'data-racket-field', racket);
		card.querySelector('.remove-customer-racket').hidden = Boolean(racket.id);

		const stringingList = card.querySelector('[data-stringing-list]');
		(racket.stringings || []).forEach((record) => this.addStringing(stringingList, record));

		Dom.find('#customer-rackets').appendChild(card);

		return card;
	},

	addStringing(list, record = {}) {
		const template = Dom.find('#customer-stringing-template');
		const row = template.content.firstElementChild.cloneNode(true);

		this.setFields(row, 'data-stringing-field', record);
		if (record.id) {
			row.querySelectorAll('[data-stringing-field]').forEach((field) => {
				field.disabled = true;
			});
			row.querySelector('.remove-stringing-record').hidden = true;
		}

		list.appendChild(row);

		return row;
	},

	addShoe(shoe = {}) {
		const template = Dom.find('#customer-shoe-template');
		const card = template.content.firstElementChild.cloneNode(true);

		this.setFields(card, 'data-shoe-field', shoe);
		card.querySelector('.remove-customer-shoe').hidden = Boolean(shoe.id);
		Dom.find('#customer-shoes').appendChild(card);

		return card;
	},

	setFields(container, attribute, values) {
		container.querySelectorAll(`[${attribute}]`).forEach((field) => {
			const key = field.getAttribute(attribute);
			field.value = values[key] ?? '';
		});
	},
};

export default Renderer;