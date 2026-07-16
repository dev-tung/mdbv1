import State from './state.js';

import Dom from '../../../../helpers/dom.js';
import Formatter from '../../../../helpers/formatter.js';

import Option from '../../../../shared/option.js';

import Select from '../../../../components/select.js';

const Renderer = {
	/* =================================================
	   PUBLIC
	================================================= */

	render() {
		this.renderOptions();

		this.renderOrder();

		this.renderProducts();
	},

	/* =================================================
	   OPTIONS
	================================================= */

	renderOptions() {
		Select.render(
			'#status',
			Option.process,
			State.order.status,
			'-- Chọn trạng thái --',
		);

		Select.render(
			'#payment',
			Option.payment,
			State.order.payment,
			'-- Chọn thanh toán --',
		);
	},

	/* =================================================
	   ORDER
	================================================= */

	renderOrder() {
		const order = State.order;

		Dom.value('#order_id', order.id);

		Dom.value('#customer_id', order.customer_id);

		Dom.value('#customer_search', order.customer_name);

		Dom.value('#description', order.description);

		Dom.value('#vat_rate', order.vat_rate);

		Dom.value('#paid_amount', order.paid_amount);
	},

	/* =================================================
	   PRODUCTS
	================================================= */

	renderProducts() {
		const tbody = Dom.find('#selected_products');

		Dom.clear('#selected_products');

		State.items.forEach((item, index) => {
			
			const fragment = Dom.template('#order-item-template');

			const row = fragment.querySelector('tr');

			row.dataset.index = index;

			// TEXT

			const texts = {
				'.product-name': item.product_name,

				'.subtotal_amount': Formatter.money(item.subtotal_amount),

				'.vat_amount': Formatter.money(item.vat_amount),

				'.total_amount': Formatter.money(item.total_amount),
			};

			Object.entries(texts).forEach(([selector, value]) => {
				Dom.text(selector, value, row);
			});

			// INPUT

			const values = {
				'.quantity': item.quantity,

				'.selling-price': item.selling_price,
			};

			Object.entries(values).forEach(([selector, value]) => {
				Dom.value(selector, value, row);
			});

			// GIFT CHECKBOX

			const gift = row.querySelector('.is-gift');

			if (gift) {
				gift.checked = Boolean(item.is_gift);
			}

			tbody.appendChild(fragment);
		});

		this.renderSummary();
	},

	renderCaculation() {
		const rows = Dom.find('#selected_products').rows;

		State.items.forEach((item, index) => {
			const row = rows[index];

			if (!row) {
				return;
			}

			Dom.text(
				'.subtotal_amount',
				Formatter.money(item.subtotal_amount),
				row,
			);

			Dom.text('.vat_amount', Formatter.money(item.vat_amount), row);

			Dom.text('.total_amount', Formatter.money(item.total_amount), row);
		});

		this.renderSummary();
	},

	/* =================================================
	   SUMMARY
	================================================= */

	renderSummary() {
		const summary = State.summary;

		Dom.text('#subtotal_amount', Formatter.money(summary.subtotal_amount));

		Dom.text('#vat_amount', Formatter.money(summary.vat_amount));

		Dom.text('#total_amount', Formatter.money(summary.total_amount));

		Dom.text('#debt_amount', Formatter.money(summary.debt_amount));
	},
};

export default Renderer;
