import Dom from '../../../../helpers/dom.js';

import Api from './api.js';
import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Controller = {
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
		const id = Dom.find('#supplier_id')?.value || null;

		if (!id) {
			return;
		}

		const supplier = await Service.getSupplier(id);

		State.setSupplier(supplier);
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
		Dom.find('#supplier-form').addEventListener('submit', async (e) => {
			e.preventDefault();

			if (!confirm('Bạn có muốn lưu không?')) {
				return;
			}

			try {
				const id = State.form.id;

				const payload = id ? Service.updatePayload(id, State.form) : Service.payload(State.form);

				const response = id ? await Api.updateSupplier(payload) : await Api.createSupplier(payload);

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

export default Controller;

document.addEventListener('DOMContentLoaded', () => {
	Controller.init();
});
