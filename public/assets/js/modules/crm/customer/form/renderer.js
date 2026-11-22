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

	addRacket(racket = {}, focus = false) {
		const template = Dom.find('#customer-racket-template');
		const card = template.content.firstElementChild.cloneNode(true);
		const container = Dom.find('#customer-rackets');

		card.querySelector('.equipment-card-title').textContent = `Vợt ${container.children.length + 1}`;
		this.setFields(card, 'data-racket-field', racket);
		card.querySelector('.remove-customer-racket').hidden = Boolean(racket.id);

		const stringingList = card.querySelector('[data-stringing-list]');
		(racket.stringings || []).forEach((record) => this.addStringing(stringingList, record));

		container.appendChild(card);

		if (focus) {
			this.focusFirstField(card);
		}

		return card;
	},

	addStringing(list, record = {}, focus = false) {
		const template = Dom.find('#customer-stringing-template');
		const row = template.content.firstElementChild.cloneNode(true);

		list.closest('[data-stringing-section]').classList.remove('d-none');
		this.setFields(row, 'data-stringing-field', record);
		if (record.id) {
			row.querySelectorAll('[data-stringing-field]').forEach((field) => {
				field.disabled = true;
			});
			row.querySelector('.remove-stringing-record').hidden = true;
		}

		list.appendChild(row);

		if (focus) {
			this.focusFirstField(row);
		}

		return row;
	},

	addShoe(shoe = {}, focus = false) {
		const template = Dom.find('#customer-shoe-template');
		const card = template.content.firstElementChild.cloneNode(true);
		const container = Dom.find('#customer-shoes');

		card.querySelector('.equipment-card-title').textContent = `Giày ${container.children.length + 1}`;
		this.setFields(card, 'data-shoe-field', shoe);
		card.querySelector('.remove-customer-shoe').hidden = Boolean(shoe.id);
		container.appendChild(card);

		if (focus) {
			this.focusFirstField(card);
		}

		return card;
	},

	focusFirstField(container) {
		const input = container.querySelector('input:not([type="hidden"]):not(:disabled)');

		if (!input) {
			return;
		}

		requestAnimationFrame(() => {
			input.focus();
			input.scrollIntoView({ behavior: 'smooth', block: 'center' });
		});
	},

	setFields(container, attribute, values) {
		container.querySelectorAll(`[${attribute}]`).forEach((field) => {
			const key = field.getAttribute(attribute);
			field.value = values[key] ?? '';
		});
	},
};

export default Renderer;