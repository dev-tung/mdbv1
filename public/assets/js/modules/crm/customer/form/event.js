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

		this.bindSubmit();
	},

	bindField(selector, field, event = 'input') {
		Dom.find(selector).addEventListener(event, (e) => {
			console.log('FIELD:', field);
			console.log('VALUE:', e.target.value);

			State.setField(field, e.target.value);

			console.log('STATE:', State.form);
		});
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
				Renderer.render();

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

				const payload = id
					? Service.updatePayload(id, State.form)
					: Service.payload(State.form);

				console.log('ID:', id);
				console.log('STATE:', State.form);
				console.log('PAYLOAD:', payload);	

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