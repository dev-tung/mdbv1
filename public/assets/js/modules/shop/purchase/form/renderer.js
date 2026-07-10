import State from './State.js';

import Dom from '../../../helpers/dom.js';

import Formatter from '../../../helpers/formatter.js';

const Renderer = {
	init() {
		this.purchase();
		this.warehouses();
		this.products();
		this.summary();
		this.payment();
	},

	/* ===============================
	   PURCHASE
	=============================== */

	purchase() {
		const purchase = State.purchase;

		const fields = {
			'#supplier_search': purchase.supplier_name,

			'#supplier_id': purchase.supplier_id,

			'#warehouse_id': purchase.warehouse_id,

			'#description': purchase.description,

			'#status': purchase.status ?? 'draft',

			'#payment': purchase.payment ?? 'unpaid',

			'#paid_amount': purchase.paid_amount ?? 0,

			'#vat_rate': purchase.vat_rate ?? 0,
		};

		Object.entries(fields).forEach(([selector, value]) => {
			Dom.setValue(selector, value);
		});
	},

	/* ===============================
	   WAREHOUSE
	=============================== */

	warehouses() {
		const select = Dom.query('#warehouse_id');

		if (!select) return;

		select.innerHTML = `
			<option value="">
				-- Chọn kho --
			</option>
		`;

		State.warehouse.list.forEach((warehouse) => {
			select.insertAdjacentHTML(
				'beforeend',
				`
				<option 
					value="${warehouse.id}"
					${warehouse.id == State.purchase.warehouse_id ? 'selected' : ''}>
					${warehouse.name}
				</option>
				`,
			);
		});
	},

	/* ===============================
	   PRODUCTS
	=============================== */

	products() {
		const tbody = Dom.query('#selected_products');

		if (!tbody) return;

		tbody.innerHTML = State.purchase.items
			.map(
				(item, index) => `

			<tr data-index="${index}">

				<td>${item.product_name}</td>


				<td width="80">
					<input
						type="number"
						class="form-control quantity"
						value="${item.quantity ?? 0}"
						min="1">
				</td>


				<td width="140">
					<input
						type="number"
						class="form-control purchase-price"
						value="${item.purchase_price ?? 0}">
				</td>


				<td width="140">
					<input
						type="number"
						class="form-control selling_price"
						value="${item.selling_price ?? 0}">
				</td>


				<td class="subtotal-amount">
					${Formatter.money(item.subtotal_amount)}
				</td>


				<td class="item-vat">
					${Formatter.money(item.vat_amount)}
				</td>


				<td class="item-total">
					${Formatter.money(item.total_amount_with_vat)}
				</td>


				<td>
					<button
						type="button"
						class="btn btn-sm btn-outline-danger btn-remove">
						Xóa
					</button>
				</td>

			</tr>

		`,
			)
			.join('');
	},

	amount(index) {
		const row = Dom.query(`tr[data-index="${index}"]`);

		if (!row) return;

		const item = State.purchase.items[index];

		row.querySelector('.subtotal-amount').textContent = Formatter.money(item.subtotal_amount);

		row.querySelector('.item-vat').textContent = Formatter.money(item.vat_amount);

		row.querySelector('.item-total').textContent = Formatter.money(item.total_amount_with_vat);
	},

	/* ===============================
	   SUMMARY
	=============================== */

	summary() {
		const summary = {
			'#subtotal_amount': State.purchase.subtotal_amount,

			'#vat_amount': State.purchase.vat_amount,

			'#total_amount': State.purchase.total_amount,

			'#debt_amount': State.purchase.debt_amount,
		};

		Object.entries(summary).forEach(([selector, value]) => {
			Dom.setText(selector, Formatter.money(value));
		});
	},

	/* ===============================
	   PAYMENT
	=============================== */

	payment() {
		const wrapper = Dom.query('#paid_amount_wrapper');

		if (!wrapper) return;

		Dom.toggle(wrapper, !['partial', 'credit'].includes(State.purchase.payment));
	},
};

export default Renderer;
