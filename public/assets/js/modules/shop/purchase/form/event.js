import Dom from '../../../../helpers/dom.js';

import Autocomplete from '../../../../components/autocomplete.js';

import Api from './api.js';
import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Event = {
	/* =================================================
       PUBLIC
    ================================================= */

	bind() {
		this.bindSupplier();
		this.bindProduct();
		this.bindPurchase();
		this.bindItems();
		this.bindSubmit();
	},

	/* =================================================
       SUPPLIER
    ================================================= */

	bindSupplier() {
		Autocomplete.init({
			element: '#supplier_search',

			source: Api.searchSupplier,

			render: Renderer.renderSupplierOption,

			select(supplier) {
				State.setSupplier(supplier);

				Renderer.render();
			},
		});
	},

	/* =================================================
       PRODUCT
    ================================================= */

	bindProduct() {
		Autocomplete.init({
			element: '#product_search',

			source: Api.searchProduct,

			render: Renderer.renderProductOption,

			select(product) {
				State.items = Service.selectProduct(State.items, product);

				Renderer.render();
			},
		});
	},

	/* =================================================
       PURCHASE
    ================================================= */

	bindPurchase() {
		Dom.find('#vat_rate').addEventListener('change', (e) => {
			State.purchase.vat_rate = Number(e.target.value);

			Renderer.render();
		});

		Dom.find('#paid_amount').addEventListener('input', (e) => {
			State.purchase.paid_amount = Number(e.target.value);

			Renderer.render();
		});
	},

	/* =================================================
       ITEMS
    ================================================= */

	bindItems() {
		const table = Dom.find('#selected_products');

		if (!table) {
			return;
		}

		table.addEventListener('input', (e) => {
			const row = e.target.closest('tr');

			if (!row) {
				return;
			}

			const index = Number(row.dataset.index);

			let field = null;

			if (e.target.classList.contains('quantity')) {
				field = 'quantity';
			}

			if (e.target.classList.contains('purchase-price')) {
				field = 'purchase_price';
			}

			if (e.target.classList.contains('selling-price')) {
				field = 'selling_price';
			}

			if (!field) {
				return;
			}

			State.items = Service.changeItem(State.items, index, field, e.target.value);

			Renderer.render();
		});

		table.addEventListener('click', (e) => {
			if (!e.target.matches('.btn-remove-item')) {
				return;
			}

			const row = e.target.closest('tr');

			if (!row) {
				return;
			}

			State.items = Service.removeItem(State.items, Number(row.dataset.index));

			Renderer.render();
		});
	},

	/* =================================================
       SUBMIT
    ================================================= */

	bindSubmit() {
		Dom.find('#purchase-form').addEventListener('submit', async (e) => {
			e.preventDefault();

			await Api.save(State);
		});
	},
};

export default Event;
