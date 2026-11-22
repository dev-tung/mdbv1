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
			Renderer.addRacket({}, true);
		});

		Dom.find('#add-customer-shoe').addEventListener('click', () => {
			Renderer.addShoe({}, true);
		});

		const rackets = Dom.find('#customer-rackets');
		const stringingPicker = Dom.find('#customer-stringing-picker');
		const stringingOptions = Dom.find('#customer-stringing-racket-options');

		Dom.find('#add-customer-stringing').addEventListener('click', () => {
			if (rackets.children.length === 0) {
				alert('Hãy thêm vợt trước khi thêm thông tin căng cước');
				return;
			}

			if (rackets.children.length === 1) {
				Renderer.addStringing(
					rackets.children[0].querySelector('[data-stringing-list]'),
					{},
					true,
				);
				return;
			}

			this.renderStringingRacketPicker([...rackets.children]);
		});

		stringingOptions.addEventListener('click', (event) => {
			const button = event.target.closest('.select-stringing-racket');

			if (!button) {
				return;
			}

			const racket = rackets.children[Number(button.dataset.racketIndex)];

			if (!racket) {
				return;
			}

			Renderer.addStringing(
				racket.querySelector('[data-stringing-list]'),
				{},
				true,
			);
			stringingPicker.classList.add('d-none');
		});

		Dom.find('#cancel-stringing-picker').addEventListener('click', () => {
			stringingPicker.classList.add('d-none');
		});

		rackets.addEventListener('click', (event) => {
			const target = event.target;
			const removeStringing = target.closest('.remove-stringing-record');
			const removeRacket = target.closest('.remove-customer-racket');

			if (removeStringing) {
				const record = removeStringing.closest('[data-stringing-record]');
				const list = record.parentElement;
				const section = list.closest('[data-stringing-section]');

				record.remove();

				if (list.children.length === 0) {
					section.classList.add('d-none');
				}
			} else if (removeRacket) {
				removeRacket.closest('.card').remove();
				this.renumberEquipment(rackets, 'Vợt');
				stringingPicker.classList.add('d-none');
			}
		});

		const shoes = Dom.find('#customer-shoes');

		shoes.addEventListener('click', (event) => {
			const removeShoe = event.target.closest('.remove-customer-shoe');

			if (removeShoe) {
				removeShoe.closest('.card').remove();
				this.renumberEquipment(shoes, 'Giày');
			}
		});
	},

	renumberEquipment(container, label) {
		[...container.children].forEach((card, index) => {
			card.querySelector('.equipment-card-title').textContent = `${label} ${index + 1}`;
		});
	},

	renderStringingRacketPicker(rackets) {
		const picker = Dom.find('#customer-stringing-picker');
		const options = Dom.find('#customer-stringing-racket-options');

		options.replaceChildren();

		rackets.forEach((racket, index) => {
			const brand = racket.querySelector('[data-racket-field="brand"]').value.trim();
			const model = racket.querySelector('[data-racket-field="model"]').value.trim();
			const button = document.createElement('button');

			button.type = 'button';
			button.className = 'btn btn-sm btn-outline-secondary text-start select-stringing-racket';
			button.dataset.racketIndex = index;
			button.textContent = [brand, model].filter(Boolean).join(' ') || `Vợt ${index + 1}`;
			options.appendChild(button);
		});

		picker.classList.remove('d-none');
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