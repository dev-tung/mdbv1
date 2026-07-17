import Dom from '../../../../helpers/dom.js';

import Autocomplete from '../../../../components/autocomplete.js';

import Api from './api.js';
import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Controller = {
	async init() {
		await this.loadDefault();

		Renderer.render();

		this.bindSupplier();
		this.bindProduct();
		this.bindPurchase();
		this.bindItems();
		this.bindSubmit();
	},

	async loadDefault() {
		const purchase_id = Dom.find('#purchase_id').value;
		const data = await Service.getDefault(purchase_id);

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
				State.setSummary();
				Renderer.renderSummary();
			},
		});
	},

	/* =================================================
       PURCHASE
    ================================================= */

	bindPurchase() {
		Dom.find('#supplier_id').addEventListener('change', (e) => {
			State.purchase.supplier_id = Number(e.target.value);
		});

		Dom.find('#warehouse_id').addEventListener('change', (e) => {
			State.purchase.warehouse_id = Number(e.target.value);
		});

		Dom.find('#status').addEventListener('change', (e) => {
			State.purchase.status = e.target.value;
		});

		Dom.find('#vat_rate').addEventListener('input', (e) => {
			State.purchase.vat_rate = Number(e.target.value);

			State.items = State.items.map((item) =>
				Service.calculateItem(item, State.purchase.vat_rate),
			);

			State.setSummary();
			Renderer.renderCaculation();
		});

		Dom.find('#payment').addEventListener('change', (e) => {
			State.purchase.payment = e.target.value;

			switch (State.purchase.payment) {
				case 'paid':
					State.purchase.paid_amount = State.summary.total_amount;
					break;

				case 'unpaid':
					State.purchase.paid_amount = 0;
					break;
			}

			State.purchase.debt_amount = State.summary.total_amount - State.purchase.paid_amount;

			Dom.find('#paid_amount_wrapper').classList.toggle(
				'd-none',
				State.purchase.payment !== 'partial',
			);

			this.renderSummary();
		});

		Dom.find('#paid_amount').addEventListener('input', (e) => {
			State.purchase.paid_amount = Number(e.target.value);

			this.renderSummary();
		});

		Dom.find('#description').addEventListener('input', (e) => {
			State.purchase.description = e.target.value;
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
			Renderer.renderCaculation();
			this.renderSummary();
		});

		table.addEventListener('click', (event) => {
			if (event.target.matches('.remove-item')) {
				State.items = Service.removeItem(State.items, event);

				Renderer.renderProducts();
				this.renderSummary();
			}
		});
	},

	/* =================================================
       SUBMIT
    ================================================= */

	bindSubmit() {
		Dom.find('#purchase-form').addEventListener('submit', async (e) => {
			e.preventDefault();

			if (!confirm('Bạn có muốn lưu không?')) {
				return;
			}

			const payload = Service.payload(State.purchase, State.summary, State.items);

			const response = State.purchase.id
				? await Api.updatePurchase(State.purchase.id, payload)
				: await Api.createPurchase(payload);

			alert(response.message);

			if (response.redirect) {
				window.location.href = response.redirect;
			}
		});
	},
};

export default Controller;

document.addEventListener('DOMContentLoaded', () => {
	Controller.init();
});
