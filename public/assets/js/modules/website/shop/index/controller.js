import Dom from '../../../../helpers/dom.js';

import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Controller = {
	async init() {
		this.bindEvents();

		await this.loadProducts();
	},

	/* =================================================
	   PRODUCTS
	================================================= */

	async loadProducts(page = 1) {
		try {
			const data = await Service.getList({
				...State.filters,

				page,

				per_page: State.pagination.per_page,
			});

			State.setDefault(data);

			State.onPageChange = (page) => {
				this.loadProducts(page);
			};

			Renderer.render();
		} catch (error) {
			alert(error.message);
		}
	},

	/* =================================================
	   EVENTS
	================================================= */

	bindEvents() {
		// =========================
		// KEYWORD
		// =========================

		Dom.find('#filter-keyword')?.addEventListener('input', async (e) => {
			State.filters.keyword = e.target.value.trim();

			await this.loadProducts();
		});

		// =========================
		// CATEGORY
		// =========================

		Dom.find('#filter-category')?.addEventListener('change', async (e) => {
			State.filters.category_id = e.target.value;

			await this.loadProducts();
		});

		// =========================
		// BRAND
		// =========================

		Dom.find('#filter-brand')?.addEventListener('change', async (e) => {
			State.filters.brand_id = e.target.value;

			await this.loadProducts();
		});

		// =========================
		// PRICE
		// =========================

		Dom.find('#filter-price')?.addEventListener('change', async (e) => {
			State.filters.price = e.target.value;

			await this.loadProducts();
		});

		// =========================
		// STATUS
		// =========================

		Dom.find('#filter-status')?.addEventListener('change', async (e) => {
			State.filters.status = e.target.value;

			await this.loadProducts();
		});
	},
};

export default Controller;

document.addEventListener('DOMContentLoaded', () => {
	Controller.init();
});