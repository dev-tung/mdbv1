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

	/* =================================================
	   PURCHASE
	================================================= */

	purchase() {
		const purchase = State.purchase;

		Dom.setValue('#supplier_search', purchase.supplier_name);

		Dom.setValue('#supplier_id', purchase.supplier_id);

		Dom.setValue('#warehouse_id', purchase.warehouse_id);

		Dom.setValue('#description', purchase.description);

		Dom.setValue('#status', purchase.status ?? 'draft');

		Dom.setValue('#payment', purchase.payment ?? 'unpaid');

		Dom.setValue('#paid_amount', purchase.paid_amount ?? 0);

		Dom.setValue('#vat_rate', purchase.vat_rate ?? 0);
	},

	/* =================================================
	   WAREHOUSE
	================================================= */

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

	/* =================================================
	   PRODUCTS
	================================================= */

	products() {
		const tbody = Dom.query('#selected_products');

		if (!tbody) return;

		tbody.innerHTML = '';

		State.purchase.items.forEach((item, index) => {
			tbody.insertAdjacentHTML(
				'beforeend',
				`

				<tr data-index="${index}">


					<!-- PRODUCT NAME -->

					<td>
						${item.product_name}
					</td>



					<!-- QUANTITY -->

					<td width="80">

						<input
							type="number"
							class="form-control quantity"
							value="${item.quantity ?? 0}"
							min="1">

					</td>



					<!-- PURCHASE PRICE -->

					<td width="140">

						<input
							type="number"
							class="form-control purchase-price"
							value="${item.purchase_price ?? 0}"
							min="0"
							step="0.01">

					</td>



					<!-- SELLING PRICE -->

					<td width="140">

						<input
							type="number"
							class="form-control selling_price"
							value="${item.selling_price ?? 0}"
							min="0"
							step="0.01">

					</td>



					<!-- SUBTOTAL -->

					<td class="subtotal-amount">

						${Formatter.money(item.subtotal_amount)}

					</td>



					<!-- VAT -->

					<td class="item-vat">

						${Formatter.money(item.vat_amount)}

					</td>



					<!-- TOTAL -->

					<td class="item-total">

						${Formatter.money(item.total_amount_with_vat)}

					</td>



					<!-- REMOVE -->

					<td width="60">

						<button
							type="button"
							class="btn btn-sm btn-outline-danger btn-remove">

							Xóa

						</button>

					</td>


				</tr>

				`,
			);
		});
	},

	amount(index) {
		const row = Dom.query(`tr[data-index="${index}"]`);

		if (!row) return;

		const item = State.purchase.items[index];

		row.querySelector('.subtotal-amount').textContent = Formatter.money(item.subtotal_amount);

		row.querySelector('.item-vat').textContent = Formatter.money(item.vat_amount);

		row.querySelector('.item-total').textContent = Formatter.money(item.total_amount_with_vat);
	},

	/* =================================================
	   SUMMARY
	================================================= */

	summary() {
		Dom.setText('#subtotal_amount', Formatter.money(State.purchase.subtotal_amount));

		Dom.setText('#vat_amount', Formatter.money(State.purchase.vat_amount));

		Dom.setText('#total_amount', Formatter.money(State.purchase.total_amount));

		Dom.setText('#debt_amount', Formatter.money(State.purchase.debt_amount));
	},

	/* =================================================
	   PAYMENT
	================================================= */

	payment() {
		const wrapper = Dom.query('#paid_amount_wrapper');

		if (!wrapper) return;

		const payment = State.purchase.payment;

		Dom.toggle(wrapper, payment !== 'partial' && payment !== 'credit');
	},
};

export default Renderer;
