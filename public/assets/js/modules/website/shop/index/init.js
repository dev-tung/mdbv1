import Dom from '../../../../helpers/dom.js';

import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Init = {
	async init() {
		// =========================
		// GET FILTER FROM URL (ONLY ONCE)
		// =========================

		const params = new URLSearchParams(window.location.search);

		State.filters.keyword = params.get('keyword') || '';
		State.filters.category_id = params.get('category') || '';

		const searchInput = Dom.find('input[name="keyword"]');
		if (searchInput) {
			searchInput.value = State.filters.keyword;
		}

		const categorySelect = Dom.find('#filter-category');
		if (categorySelect) {
			categorySelect.value = State.filters.category_id;
		}

		this.bindEvents();

		await this.loadProducts();
	},

	/* =================================================
		PRODUCTS
	================================================= */

	async loadProducts(page = 1) {
		try {
			// Nếu có ô tìm kiếm thì luôn lấy giá trị hiện tại
			const searchInput = Dom.find('input[name="keyword"]');

			if (searchInput) {
				State.filters.keyword = searchInput.value.trim();
			}

			const data = await Service.getList({
				...State.filters,
				website: 1,
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

export default Init;

document.addEventListener('DOMContentLoaded', () => {
	Init.init();
});