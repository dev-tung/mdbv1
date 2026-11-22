import Dom from '../../../../helpers/dom.js';

import Api from './api.js';
import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Event = {
	/* =================================================
	   PUBLIC
	================================================= */

	async init() {
		await this.loadDefault();

		Renderer.render();

		this.bindEvents();
	},

	/* =================================================
	   DEFAULT
	================================================= */

	async loadDefault() {
		const id = Dom.find('#customer_id')?.value || null;

		const customerGroup = await Service.getCustomerGroups();

		State.setCustomerGroup(customerGroup);

		if (!id) {
			return;
		}

		const customer = await Service.getCustomer(id);

		State.setCustomer(customer);
	},

	/* =================================================
	   EVENTS
	================================================= */

	bindEvents() {
		this.bindField('#name', 'name');

		this.bindField('#customer_group_id', 'group_id', 'change');

		this.bindField('#phone', 'phone');

		this.bindField('#email', 'email');

		this.bindField('#address', 'address');

		this.bindField('#description', 'description');

		this.bindCustomerGroup();

		this.bindEquipment();

		this.bindSubmit();
	},

	bindField(selector, field, event = 'input') {
		Dom.find(selector).addEventListener(event, (e) => {
			State.setField(field, e.target.value);
		});
	},

	bindEquipment() {
		Dom.find('#add-customer-racket').addEventListener('click', () => {
			Renderer.addRacket();
		});

		Dom.find('#add-customer-shoe').addEventListener('click', () => {
			Renderer.addShoe();
		});

		Dom.find('#customer-rackets').addEventListener('click', (event) => {
			const target = event.target;
			const addStringing = target.closest('.add-stringing-record');
			const removeStringing = target.closest('.remove-stringing-record');
			const removeRacket = target.closest('.remove-customer-racket');

			if (addStringing) {
				const card = addStringing.closest('.card');
				Renderer.addStringing(card.querySelector('[data-stringing-list]'));
			} else if (removeStringing) {
				removeStringing.closest('[data-stringing-record]').remove();
			} else if (removeRacket) {
				removeRacket.closest('.card').remove();
			}
		});

		Dom.find('#customer-shoes').addEventListener('click', (event) => {
			const removeShoe = event.target.closest('.remove-customer-shoe');

			if (removeShoe) {
				removeShoe.closest('.card').remove();
			}
		});
	},

	collectEquipment() {
		const rackets = [...Dom.find('#customer-rackets').querySelectorAll('.card')]
			.map((card) => {
				const racket = {};
				card.querySelectorAll('[data-racket-field]').forEach((field) => {
					racket[field.dataset.racketField] = field.value.trim();
				});
				racket.stringings = [...card.querySelectorAll('[data-stringing-record]')].map((row) => {
					const record = {};
					row.querySelectorAll('[data-stringing-field]').forEach((field) => {
						record[field.dataset.stringingField] = field.value.trim();
					});
					return record;
				});
				return racket;
			})
			.filter((racket) =>
				racket.id ||
				racket.brand ||
				racket.model ||
				racket.note ||
				racket.stringings.some((record) => record.id || record.string_type || record.tension || record.strung_at || record.note),
			);

		const shoes = [...Dom.find('#customer-shoes').querySelectorAll('.card')]
			.map((card) => {
				const shoe = {};
				card.querySelectorAll('[data-shoe-field]').forEach((field) => {
					shoe[field.dataset.shoeField] = field.value.trim();
				});
				return shoe;
			})
			.filter((shoe) => shoe.id || shoe.brand || shoe.model || shoe.size || shoe.note);

		return { rackets, shoes };
	},

	/* =================================================
	   CUSTOMER GROUP
	================================================= */

	bindCustomerGroup() {
		Dom.find('#btn-save-customer-group').addEventListener('click', async () => {
			const input = Dom.find('#customer-group-name');

			const name = input.value.trim();

			if (!name) {
				alert('Vui lòng nhập tên nhóm khách hàng');

				input.focus();

				return;
			}

			try {
				const response = await Api.createCustomerGroup({
					name: name,
				});

				alert(response.message);

				const customerGroup = response.data;

				// Chọn nhóm vừa tạo
				State.setField(
					'group_id',
					customerGroup.id
				);

				// Load lại options
				const options = await Service.getCustomerGroups();

				State.setCustomerGroup(options);

				// Render lại
				Renderer.renderCustomerGroup();

				// Reset input
				input.value = '';

				// Đóng modal
				const modal = Dom.find('#customer-group-modal');

				const modalInstance = bootstrap.Modal.getInstance(modal);

				modalInstance?.hide();

			} catch (error) {
				alert(error.message);
			}
		});
	},

	/* =================================================
	   SUBMIT
	================================================= */

	bindSubmit() {
		Dom.find('#customer-form').addEventListener('submit', async (e) => {
			e.preventDefault();

			if (!confirm('Bạn có muốn lưu không?')) {
				return;
			}

			try {
				const id = State.form.id;

				const form = { ...State.form, ...this.collectEquipment() };
				const payload = id
					? Service.updatePayload(id, form)
					: Service.payload(form);

				const response = id
					? await Api.updateCustomer(payload)
					: await Api.createCustomer(payload);

				alert(response.message);

				if (response.redirect) {
					window.location.href = response.redirect;
				}
			} catch (error) {
				alert(error.message);
			}
		});
	},
};

export default Event;

document.addEventListener('DOMContentLoaded', () => {
	Event.init();
});