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

		this.bindCustomer();
		this.bindProduct();
		this.bindOrder();
		this.bindItems();
		this.bindSubmit();
	},

	async loadDefault() {
		const order_id = Dom.find('#order_id').value;
		const data = await Service.getDefault(order_id);

		State.setDefault(data);

		this.renderSummary();
	},

	renderSummary() {
		State.setSummary();

		Renderer.renderSummary();
	},

	/* =================================================
	   CUSTOMER
	================================================= */

	bindCustomer() {
		Autocomplete.init({
			element: '#customer_search',

			async source(keyword) {
				const customers = await Api.searchCustomer(keyword);

				return customers.data;
			},

			select(customer) {
				State.setCustomer(customer);

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
			field: 'product_name',
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
	   ORDER
	================================================= */

	bindOrder() {
		Dom.find('#customer_id').addEventListener('change', (e) => {
			State.order.customer_id = Number(e.target.value);
		});

		Dom.find('#status').addEventListener('change', (e) => {
			State.order.status = e.target.value;
		});

		Dom.find('#vat_rate').addEventListener('input', (e) => {
			State.order.vat_rate = Number(e.target.value);

			State.items = State.items.map((item) => Service.calculateItem(item, State.order.vat_rate));

			State.setSummary();

			Renderer.renderCaculation();
		});

		Dom.find('#payment').addEventListener('change', (e) => {
			State.order.payment = e.target.value;

			switch (State.order.payment) {
				case 'paid':
					State.order.paid_amount = State.summary.total_amount;

					break;

				case 'unpaid':
					State.order.paid_amount = 0;

					break;
			}

			State.order.debt_amount = State.summary.total_amount - State.order.paid_amount;

			Dom.find('#paid_amount_wrapper').classList.toggle('d-none', State.order.payment !== 'partial');

			this.renderSummary();
		});

		Dom.find('#paid_amount').addEventListener('input', (e) => {
			State.order.paid_amount = Number(e.target.value);

			this.renderSummary();
		});

		Dom.find('#description').addEventListener('input', (e) => {
			State.order.description = e.target.value;
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

		const changeItem = (e) => {
			if (e.target.matches('.quantity')) {
				const row = e.target.closest('tr');
				const item = State.items[Number(row.dataset.index)];
				const quantity = Number(e.target.value);

				if (quantity > item.quantity) {
					alert(`Số lượng tồn chỉ còn ${item.quantity}.`);

					e.target.value = item.quantity;

					return;
				}
			}

			State.items = Service.changeItem(State.items, e, State.order.vat_rate);

			Renderer.renderCaculation();

			this.renderSummary();
		};

		const changeGift = (e) => {
			State.items = Service.changeGift(State.items, e);

			State.order.paid_amount = 0;
			Renderer.renderCaculation();
			this.renderSummary();
		};

		const removeItem = (e) => {
			State.items = Service.removeItem(State.items, e);

			Renderer.renderProducts();

			this.renderSummary();
		};

		table.addEventListener('input', (e) => {
			if (!e.target.matches('.quantity, .selling-price')) {
				return;
			}

			changeItem(e);
		});

		table.addEventListener('change', (e) => {
			if (e.target.matches('.quantity, .selling-price')) {
				changeItem(e);
				return;
			}

			if (!e.target.matches('.is-gift')) {
				return;
			}

			changeGift(e);
		});

		table.addEventListener('click', (e) => {
			if (!e.target.matches('.remove-item')) {
				return;
			}

			removeItem(e);
		});
	},

	/* =================================================
	   SUBMIT
	================================================= */

	bindSubmit() {
		Dom.find('#order-form').addEventListener('submit', async (e) => {
			e.preventDefault();

			if (!confirm('Bạn có muốn lưu không?')) {
				return;
			}

			const payload = Service.payload(State.order, State.summary, State.items);

			const response = State.order.id
				? await Api.updateOrder(State.order.id, payload)
				: await Api.createOrder(payload);

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
