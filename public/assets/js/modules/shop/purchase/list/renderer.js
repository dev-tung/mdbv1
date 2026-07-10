import State from './state.js';

import Dom from '../../../helpers/dom.js';
import Formatter from '../../../helpers/formatter.js';

import Table from '../../../components/table.js';
import Option from '../../../components/option.js';
import Pagination from '../../../components/pagination.js';

const Renderer = {
	/* ===============================
	   RENDER
	=============================== */

	render() {
		this.filters();

		this.summary();

		this.table();

		this.pagination();
	},

	/* ===============================
	   FILTERS
	=============================== */

	filters() {
		this.suppliers();

		this.payments();
	},

	suppliers() {
		const select = Dom.query('#filter-supplier');

		if (!select) return;

		select.innerHTML = `
			<option value="">
				Nhà cung cấp
			</option>

			${Option.render({
				data: State.suppliers,
				selected: State.filter.supplier_id,
			})}
		`;
	},

	payments() {
		const select = Dom.query('#filter-payment');

		if (!select) return;

		select.innerHTML = `
			<option value="">
				Thanh toán
			</option>

			${Option.render({
				data: State.options.payments,
				selected: State.filter.payment,
				label: 'label',
			})}
		`;
	},

	/* ===============================
	   SUMMARY
	=============================== */

	summary() {
		Dom.setText('#sum-total-amount', Formatter.money(State.summary.total_amount));

		Dom.setText('#sum-paid-amount', Formatter.money(State.summary.paid_amount));

		Dom.setText('#sum-debt-amount', Formatter.money(State.summary.debt_amount));
	},

	/* ===============================
	   TABLE
	=============================== */

	table() {
		Table.init({
			element: Dom.query('#purchase-table-body'),

			data: State.purchases,

			columns: 10,

			cells: (purchase, index) => `
				<td>${index + 1}</td>

				<td>${purchase.supplier_name ?? ''}</td>

				<td>${purchase.warehouse_name ?? ''}</td>

				<td>${Formatter.money(purchase.total_amount)}</td>

				<td>${Formatter.money(purchase.paid_amount)}</td>

				<td>${Formatter.money(purchase.debt_amount)}</td>

				<td>${this.status(purchase)}</td>

				<td>${this.payment(purchase)}</td>

				<td>${purchase.created_at ?? ''}</td>

				<td>${this.actions(purchase)}</td>
			`,
		});
	},

	/* ===============================
	   STATUS
	=============================== */

	status(purchase) {
		return `
			<select
				class="
					form-select
					form-select-sm
					purchase-status
					text-${this.color(State.options.statuses, purchase.status)}
				"
				data-id="${purchase.id}"
			>
				${Option.render({
					data: State.options.statuses,
					selected: purchase.status,
					label: 'label',
				})}
			</select>
		`;
	},

	/* ===============================
	   PAYMENT
	=============================== */

	payment(purchase) {
		return `
			<select
				class="
					form-select
					form-select-sm
					purchase-payment
					text-${this.color(State.options.payments, purchase.payment)}
				"
				data-id="${purchase.id}"
			>
				${Option.render({
					data: State.options.payments,
					selected: purchase.payment,
					label: 'label',
				})}
			</select>
		`;
	},

	/* ===============================
	   ACTIONS
	=============================== */

	actions(purchase) {
		return `
			<a
				href="/admin/purchases/edit/${purchase.id}"
				class="btn btn-sm btn-outline-secondary"
			>
				Sửa
			</a>

			<button
				type="button"
				class="btn btn-sm btn-outline-danger btn-delete-purchase"
				data-id="${purchase.id}"
			>
				Xóa
			</button>
		`;
	},

	/* ===============================
	   PAGINATION
	=============================== */

	pagination() {
		Pagination.init({
			element: Dom.query('#purchase-pagination'),

			current: State.pagination.current,

			total: State.pagination.total,

			onChange: (page) => {
				State.filter.page = page;

				// Service.load();
			},
		});
	},

	/* ===============================
	   COLOR
	=============================== */

	color(data = {}, value) {
		return data?.[value]?.color || '';
	},
};

export default Renderer;
