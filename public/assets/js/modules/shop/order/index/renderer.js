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
			Select.render(row.querySelector('.status'), Option.process, order.status);

			row.querySelector('.status').dataset.id = order.id;

			// Payment
			Select.render(row.querySelector('.payment'), Option.payment, order.payment);

			row.querySelector('.payment').dataset.id = order.id;

			// Edit
			row.querySelector('.edit-item').href = `/admin/orders/edit/${order.id}`;

			// Delete
			row.querySelector('.delete-item').dataset.id = order.id;

			return fragment;
		});

		Renderer.renderMobileCards();
	},

	renderMobileCards() {
		const container = Dom.find('#order-card-list');

		if (!container) {
			return;
		}

		container.replaceChildren();

		if (!State.orders.length) {
			const empty = document.createElement('p');
			empty.className = 'order-card-empty';
			empty.textContent = 'Không tìm thấy đơn hàng';
			container.appendChild(empty);
			return;
		}

		State.orders.forEach((order) => {
			const fragment = Dom.template('#order-card-template');
			const card = fragment.querySelector('.order-card');

			card.dataset.id = order.id;
			Dom.text('.order-card-customer', order.customer_name || '', card);
			Dom.text('.order-card-id', `#${order.id}`, card);
			Dom.text('.order-total-badge', `Tổng ${Formatter.money(order.total_amount)}`, card);
			Dom.text('.order-paid-badge', `Đã thu ${Formatter.money(order.paid_amount)}`, card);
			Dom.text('.order-debt-badge', `Còn nợ ${Formatter.money(order.debt_amount)}`, card);

			const createdAt = card.querySelector('.order-date-badge');
			createdAt.textContent = Formatter.datetime(order.created_at);
			createdAt.hidden = !order.created_at;

			const status = card.querySelector('.status');
			Select.render(status, Option.process, order.status);
			status.dataset.id = order.id;

			const payment = card.querySelector('.payment');
			Select.render(payment, Option.payment, order.payment);
			payment.dataset.id = order.id;

			card.querySelector('.edit-item').href = `/admin/orders/edit/${order.id}`;
			card.querySelector('.delete-item').dataset.id = order.id;

			container.appendChild(fragment);
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
