import Dom from '../../../../helpers/dom.js';

import Api from './api.js';
import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Init = {
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
		const id = Dom.find('#employee_id')?.value || null;

		if (!id) {
			return;
		}

		const employee = await Service.getEmployee(id);

		State.setEmployee(employee);
	},

	/* =================================================
	   EVENTS
	================================================= */

	bindEvents() {
		this.bindField('#name', 'name');

		this.bindField('#phone', 'phone');

		this.bindField('#email', 'email');

		this.bindField('#address', 'address');

		this.bindField('#description', 'description');

		this.bindSubmit();
	},

	bindField(selector, field, event = 'input') {
		Dom.find(selector).addEventListener(event, (e) => {
			State.setField(field, e.target.value);
		});
	},

	/* =================================================
	   SUBMIT
	================================================= */

	bindSubmit() {
		Dom.find('#employee-form').addEventListener('submit', async (e) => {
			e.preventDefault();

			if (!confirm('Bạn có muốn lưu không?')) {
				return;
			}

			try {
				const id = State.form.id;

				const payload = id ? Service.updatePayload(id, State.form) : Service.payload(State.form);

				const response = id ? await Api.updateEmployee(payload) : await Api.createEmployee(payload);

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

export default Init;

document.addEventListener('DOMContentLoaded', () => {
	Init.init();
});
