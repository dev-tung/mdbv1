import Dom from '../../../../helpers/dom.js';

import Autocomplete from '../../../../components/autocomplete.js';

import Api from './api.js';
import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Controller = {
	async init() {
		await this.setDefault();

		Renderer.render();

		this.bindSupplier();
		this.bindProduct();
		this.bindPurchase();
		this.bindItems();
		this.bindSubmit();
	},

	async setDefault() {
		const id = Dom.find('#purchase_id').value;

		const data = await Service.getDefault(id);

		State.setDefault(data);

		this.renderSummary();
	},

	renderSummary() {
		State.setSummary();
		Renderer.renderSummary();
	},

	/* =================================================
       SUPPLIER
    ================================================= */

	bindSupplier() {
		Autocomplete.init({
			element: '#supplier_search',

			async source(keyword) {
				const suppliers = await Api.searchSupplier(keyword);
				return suppliers.data;
			},

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

			async source(keyword) {
				const products = await Api.searchProduct(keyword);
				return products.data;
			},

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
		Dom.find('#vat_rate').addEventListener('input', (e) => {
			State.purchase.vat_rate = Number(e.target.value);

			State.items = State.items.map((item) => Service.calculateItem(item, State.purchase.vat_rate));

			State.setSummary();
			Renderer.renderCaculation();
		});

		Dom.find('#paid_amount').addEventListener('input', (e) => {
			State.purchase.paid_amount = Number(e.target.value);
			this.renderSummary();
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
			State.items = Service.changeItem(State.items, e, State.purchase.vat_rate);
			this.renderSummary();
		});

		table.addEventListener('click', (e) => {
			State.items = Service.removeItem(State.items, e);
			Renderer.renderProducts();
			this.renderSummary();
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

export default Controller;

document.addEventListener('DOMContentLoaded', () => {
	Controller.init();
});
