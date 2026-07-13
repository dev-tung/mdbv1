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
		Select.render(
			'#filter-payment',
			Option.payment,
			State.filters.payment,
			'-- Thanh toán --'
		);

		Select.render(
			'#filter-supplier',
			State.suppliers,
			State.filters.supplier_id,
			'-- Nhà cung cấp --'
		);
	},

	/* =================================================
       TABLE
    ================================================= */

	renderTable() {
		Table.renderBody(
			State.purchases,
			(purchase, index) => {
				const fragment = Dom.template('#purchase-row-template');

				const row = fragment.querySelector('tr');

				row.dataset.id = purchase.id;

				Dom.text('.index', index + 1, row);

				Dom.text('.supplier-name', purchase.supplier_name, row);

				Dom.text('.warehouse-name', purchase.warehouse_name, row);

				Dom.text(
					'.total-amount',
					Formatter.money(purchase.total_amount),
					row
				);

				Dom.text(
					'.paid-amount',
					Formatter.money(purchase.paid_amount),
					row
				);

				Dom.text(
					'.debt-amount',
					Formatter.money(purchase.debt_amount),
					row
				);

				Dom.text(
					'.status',
					Option.process[purchase.status]?.label ?? '',
					row
				);

				Dom.text(
					'.payment',
					Option.payment[purchase.payment]?.label ?? '',
					row
				);

				Dom.text('.created-at', purchase.created_at, row);

				return fragment;
			}
		);

		Table.renderPagination(State.pagination);
	},

	/* =================================================
       SUMMARY
    ================================================= */

	renderSummary() {
		Dom.text(
			'#sum-total-amount',
			Formatter.money(State.summary.total_amount)
		);

		Dom.text(
			'#sum-paid-amount',
			Formatter.money(State.summary.paid_amount)
		);

		Dom.text(
			'#sum-debt-amount',
			Formatter.money(State.summary.debt_amount)
		);
	},
};

export default Renderer;