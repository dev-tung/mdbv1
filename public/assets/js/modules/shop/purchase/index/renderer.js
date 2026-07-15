import State from './state.js';

import Dom from '../../../../helpers/dom.js';
import Formatter from '../../../../helpers/formatter.js';

import Option from '../../../../shared/option.js';

import Select from '../../../../components/select.js';
import Table from '../../../../components/table.js';

const Renderer = {
	/* =================================================
       PUBLIC
    ================================================= */

	render() {
		this.renderOptions();
		this.renderTable();
		this.renderSummary();
	},

	/* =================================================
       OPTIONS
    ================================================= */

	renderOptions() {
		Select.render('#filter-payment', Option.payment, State.filters.payment, '-- Thanh toán --');
		Select.render('#filter-supplier', State.suppliers, State.filters.supplier_id, '-- Nhà cung cấp --');
	},

	/* =================================================
       TABLE
    ================================================= */

	renderTable() {
		Table.renderBody(State.purchases, (purchase, index) => {
			const fragment = Dom.template('#purchase-row-template');
			const row = fragment.querySelector('tr');

			row.dataset.id = purchase.id;

			// Text
			const texts = {
				'.index': index + 1,
				'.supplier-name': purchase.supplier_name,
				'.warehouse-name': purchase.warehouse_name,
				'.total-amount': Formatter.money(purchase.total_amount),
				'.paid-amount': Formatter.money(purchase.paid_amount),
				'.debt-amount': Formatter.money(purchase.debt_amount),
				'.created-at': purchase.created_at,
			};

			Object.entries(texts).forEach(([selector, value]) => {
				Dom.text(selector, value, row);
			});

			// Select
			[
				{
					selector: '.status',
					options: Option.process,
					value: purchase.status,
				},
				{
					selector: '.payment',
					options: Option.payment,
					value: purchase.payment,
				},
			].forEach(({ selector, options, value }) => {
				const select = row.querySelector(selector);

				Select.render(select, options, value);
				select.dataset.id = purchase.id;
			});

			// Buttons
			['.edit-item', '.delete-item'].forEach((selector) => {
				row.querySelector(selector).dataset.id = purchase.id;
			});

			return fragment;
		});
	},

	/* =================================================
       SUMMARY
    ================================================= */

	renderSummary() {
		Dom.text('#sum-total-amount', Formatter.money(State.summary.total_amount));

		Dom.text('#sum-paid-amount', Formatter.money(State.summary.paid_amount));

		Dom.text('#sum-debt-amount', Formatter.money(State.summary.debt_amount));
	},
};

export default Renderer;
