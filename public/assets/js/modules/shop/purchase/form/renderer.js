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
		this.renderPurchase();
		this.renderProducts();
	},

	/* =================================================
       OPTIONS
    ================================================= */

	renderOptions() {
		Select.render(
			'#status',
			Option.process,
			State.purchase.status,
			'-- Chọn trạng thái --',
		);

		Select.render(
			'#payment',
			Option.payment,
			State.purchase.payment,
			'-- Chọn thanh toán --',
		);

		Select.render(
			'#warehouse_id',
			State.warehouses,
			State.purchase.warehouse_id,
			'-- Chọn kho --',
		);
	},

	/* =================================================
       PURCHASE
    ================================================= */

	renderPurchase() {
		const purchase = State.purchase;

		Dom.value('#purchase_id', purchase.id);
		Dom.value('#supplier_id', purchase.supplier_id);
		Dom.value('#supplier_search', purchase.supplier_name);
		Dom.value('#description', purchase.description);
		Dom.value('#vat_rate', purchase.vat_rate);
		Dom.value('#paid_amount', purchase.paid_amount);
	},

	/* =================================================
       PRODUCTS
    ================================================= */

	renderProducts() {
		const tbody = Dom.find('#selected_products');

		Dom.clear('#selected_products');

		State.items.forEach((item, index) => {
			const fragment = Dom.template('#purchase-item-template');
			const row = fragment.querySelector('tr');

			row.dataset.index = index;

			// Text
			const texts = {
				'.product-name': item.product_name,
				'.subtotal_amount': Formatter.money(item.subtotal_amount),
				'.vat_amount': Formatter.money(item.vat_amount),
				'.total_amount': Formatter.money(item.total_amount),
			};

			Object.entries(texts).forEach(([selector, value]) => {
				Dom.text(selector, value, row);
			});

			// Input
			const values = {
				'.quantity': item.quantity,
				'.purchase-price': item.purchase_price,
				'.selling-price': item.selling_price,
			};

			Object.entries(values).forEach(([selector, value]) => {
				Dom.value(selector, value, row);
			});

			tbody.appendChild(fragment);
		});
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
