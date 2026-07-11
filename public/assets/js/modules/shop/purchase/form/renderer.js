import State from './State.js';

import Dom from '../../../helpers/dom.js';
import Formatter from '../../../helpers/formatter.js';

import Option from '../../../components/option.js';
import Table from '../../../components/table.js';

const Renderer = {
	/* ===============================
	   RENDER
	=============================== */

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

		Dom.html(
			'#warehouse_id',
			`
				<option value="">
					-- Chọn kho --
				</option>

				${Option.render({
					data: State.warehouse.list,
					selected: State.purchase.warehouse_id,
				})}
			`,
		);
	},

	/* ===============================
	   PRODUCTS
	=============================== */

	products() {
		Table.init({
			element: Dom.query('#selected_products'),

			data: State.purchase.items,

			columns: 8,

			attributes: (item, index) => `
				data-index="${index}"
			`,

			cells: (item) => [
				item.product_name,

				{
					content: `
						<input
							type="number"
							class="form-control quantity"
							value="${item.quantity ?? 0}"
							min="1">
					`,
					width: 80,
				},

				{
					content: `
						<input
							type="number"
							class="form-control purchase-price"
							value="${item.purchase_price ?? 0}">
					`,
					width: 140,
				},

				{
					content: `
						<input
							type="number"
							class="form-control selling_price"
							value="${item.selling_price ?? 0}">
					`,
					width: 140,
				},

				{
					content: Formatter.money(item.subtotal_amount),
					class: 'subtotal-amount',
				},

				{
					content: Formatter.money(item.vat_amount),
					class: 'item-vat',
				},

				{
					content: Formatter.money(item.total_amount_with_vat),
					class: 'item-total',
				},

				{
					content: `
						<button
							type="button"
							class="btn btn-sm btn-outline-danger btn-remove">
							Xóa
						</button>
					`,
				},
			],
		});
	},

	amount(index) {
		const row = Dom.query(`tr[data-index="${index}"]`);

		if (!row) return;

		const item = State.purchase.items[index];

		Dom.setText('.subtotal-amount', Formatter.money(item.subtotal_amount), row);

		Dom.setText('.item-vat', Formatter.money(item.vat_amount), row);

		Dom.setText('.item-total', Formatter.money(item.total_amount_with_vat), row);
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
