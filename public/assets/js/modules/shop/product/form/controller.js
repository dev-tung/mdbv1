import Dom from '../../../../helpers/dom.js';

import Api from './api.js';
import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Controller = {
	async init() {
		await this.loadDefault();

		Renderer.render();

		this.bindProduct();

		this.bindThumbnail();

		this.bindSubmit();
	},

	/* =================================================
	   DEFAULT
	================================================= */

	async loadDefault() {
		const id = Dom.find('#product_id')?.value || null;

		const data = await Service.getOptions();

		State.setOptions(data);

		if (id) {
			const product = await Service.getProduct(id);

			State.setProduct(product);
		}
	},

	/* =================================================
	   FORM
	================================================= */

	bindProduct() {
		Dom.find('#category_id').addEventListener('change', (e) => {
			State.form.category_id = e.target.value;
		});

		Dom.find('#brand_id').addEventListener('change', (e) => {
			State.form.brand_id = e.target.value;
		});

		Dom.find('#status').addEventListener('change', (e) => {
			State.form.status = e.target.value;
		});

		Dom.find('#name').addEventListener('input', (e) => {
			State.form.name = e.target.value;
		});

		Dom.find('#price').addEventListener('input', (e) => {
			State.form.price = e.target.value;
		});

		Dom.find('#sale_price').addEventListener('input', (e) => {
			State.form.sale_price = e.target.value;
		});

		Dom.find('#description').addEventListener('input', (e) => {
			State.form.description = e.target.value;
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

			State.form.thumbnail = file;

			Renderer.renderPreview(file);
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

				const payload = id ? Service.updatePayload(id, State.form) : Service.payload(State.form);

				console.log('State.form:', State.form);

				console.log('Payload:', payload);

				const response = id ? await Api.updateProduct(payload) : await Api.createProduct(payload);

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
