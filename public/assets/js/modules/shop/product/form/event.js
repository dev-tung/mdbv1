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

        this.bindCategory();

				this.bindBrand();

        this.bindSubmit();
    },

    bindField(selector, field, event = 'input') {
        Dom.find(selector).addEventListener(event, (e) => {
            State.setField(field, e.target.value);
        });
    },

		/* =================================================
			CATEGORY
		================================================= */

		bindCategory() {
			Dom.find('#btn-save-category').addEventListener('click', async () => {
				const input = Dom.find('#category-name');

				const name = input.value.trim();

				if (!name) {
					alert('Vui lòng nhập tên danh mục');

					input.focus();

					return;
				}

				try {
					const response = await Api.createCategory({
						name: name,
					});

					alert(response.message);

					const category = response.data;

					// Load lại options
					const options = await Service.getOptions();

					State.setOptions(options);

					// Render lại
					Renderer.render();

					// Reset input
					input.value = '';

					// Đóng modal
					const modal = Dom.find('#category-modal');

					const modalInstance = bootstrap.Modal.getInstance(modal);

					modalInstance?.hide();

				} catch (error) {
					alert(error.message);
				}
			});
		},

		/* =================================================
			BRAND
		================================================= */

		bindBrand() {
			Dom.find('#btn-save-brand').addEventListener('click', async () => {
				const input = Dom.find('#brand-name');

				const name = input.value.trim();

				if (!name) {
					alert('Vui lòng nhập tên thương hiệu');

					input.focus();

					return;
				}

				try {
					const response = await Api.createBrand({
						name: name,
					});

					alert(response.message);

					const brand = response.data;

					// Load lại options
					const options = await Service.getOptions();

					State.setOptions(options);

					// Render lại
					Renderer.render();

					// Reset input
					input.value = '';

					// Đóng modal
					const modal = Dom.find('#brand-modal');

					const modalInstance = bootstrap.Modal.getInstance(modal);

					modalInstance?.hide();

				} catch (error) {
					alert(error.message);
				}
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

export default Event;

document.addEventListener('DOMContentLoaded', () => {
    Event.init();
});