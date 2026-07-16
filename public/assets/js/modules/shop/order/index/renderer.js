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
			'-- Thanh toán --',
		);
	},

	/* =================================================
	   TABLE
	================================================= */

	renderTable() {
		Table.renderBody(State.orders, (order, index) => {
			const fragment = Dom.template('#order-row-template');
			const row = fragment.querySelector('tr');

			row.dataset.id = order.id;

			// Text
			const texts = {
				'.index': index + 1,
				'.customer-name': order.customer_name,
				'.total-amount': Formatter.money(order.total_amount),
				'.paid-amount': Formatter.money(order.paid_amount),
				'.debt-amount': Formatter.money(order.debt_amount),
				'.created-at': order.created_at,
			};

			Object.entries(texts).forEach(([selector, value]) => {
				Dom.text(selector, value, row);
			});

			// Status
			Select.render(
				row.querySelector('.status'),
				Option.process,
				order.status,
			);

			row.querySelector('.status').dataset.id = order.id;

			// Payment
			Select.render(
				row.querySelector('.payment'),
				Option.payment,
				order.payment,
			);

			row.querySelector('.payment').dataset.id = order.id;

			// Edit
			row.querySelector('.edit-item').href =
				`/admin/orders/edit/${order.id}`;

			// Delete
			row.querySelector('.delete-item').dataset.id = order.id;

			return fragment;
		});
	},

	/* =================================================
	   SUMMARY
	================================================= */

	renderSummary() {
		Dom.text(
			'#sum-total-amount',
			Formatter.money(State.summary.total_amount),
		);

		Dom.text(
			'#sum-paid-amount',
			Formatter.money(State.summary.paid_amount),
		);

		Dom.text(
			'#sum-debt-amount',
			Formatter.money(State.summary.debt_amount),
		);
	},
};

export default Renderer;
