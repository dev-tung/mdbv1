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
		const id = Dom.find('#product_id')?.value || null;

		const options = await Service.getOptions();

		State.setOptions(options);

		if (!id) {
			return;
		}

		const product = await Service.getProduct(id);

		State.setProduct(product);
	},

	/* =================================================
	   EVENTS
	================================================= */

	bindEvents() {
		this.bindField('#category_id', 'category_id', 'change');

		this.bindField('#brand_id', 'brand_id', 'change');

		this.bindField('#status', 'status', 'change');

		this.bindField('#name', 'name');

		this.bindField('#price', 'price');

		this.bindField('#sale_price', 'sale_price');

		this.bindField('#description', 'description');

		this.bindThumbnail();

		this.bindSubmit();
	},

	bindField(selector, field, event = 'input') {
		Dom.find(selector).addEventListener(event, (e) => {
			State.setField(field, e.target.value);
		});
	},

	/* =================================================
	   THUMBNAIL
	================================================= */

	bindThumbnail() {
		Dom.find('#thumbnail').addEventListener('change', (e) => {
			const file = e.target.files[0];

			if (!file) {
				return;
			}

			State.setField('thumbnail', file);

			Renderer.renderThumbnail(file);
		});
	},

	/* =================================================
	   SUBMIT
	================================================= */

	bindSubmit() {
		Dom.find('#product-form').addEventListener('submit', async (e) => {
			e.preventDefault();

			if (!confirm('Bạn có muốn lưu không?')) {
				return;
			}

			try {
				const id = State.form.id;

				const payload = id
					? Service.updatePayload(id, State.form)
					: Service.payload(State.form);

				const response = id
					? await Api.updateProduct(payload)
					: await Api.createProduct(payload);

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