import Calculator from '../../../../helpers/calculator.js';

import Api from './api.js';

const Service = {
	async getDefault(order_id = null) {
		const response = order_id ? await Api.showOrder(order_id) : null;

		const [order = [], items = []] = response?.data ?? [];

		return {
			order: order[0] ?? {},

			items,
		};
	},

	/* =================================================
	   PRODUCT
	================================================= */

	selectProduct(items, product) {
		const index = items.findIndex((item) => item.product_id === product.id);

		// Sản phẩm đã tồn tại

		if (index !== -1) {
			return items.map((item, i) => {
				if (i !== index) {
					return item;
				}

				const newItem = {
					...item,

					quantity: item.quantity + 1,
				};

				return this.calculateItem(newItem, item.vat_rate ?? 0);
			});
		}

		// Sản phẩm mới

		const item = {
			product_id: product.id,

			code: product.code,

			product_name: product.name,

			quantity: 1,

			selling_price: product.selling_price ?? 0,

			vat_rate: 0,

			is_gift: 0,
		};

		return [...items, this.calculateItem(item, item.vat_rate)];
	},

	/* =================================================
	   ITEMS
	================================================= */

	changeItem(items, event, vatRate) {
		const row = event.target.closest('tr');

		if (!row) {
			return items;
		}

		const index = Number(row.dataset.index);

		let field = null;

		if (event.target.classList.contains('quantity')) {
			field = 'quantity';
		}

		if (event.target.classList.contains('selling-price')) {
			field = 'selling_price';
		}

		if (!field) {
			return items;
		}

		return items.map((item, i) => {
			if (i !== index) {
				return item;
			}

			return this.calculateItem(
				{
					...item,

					[field]: Number(event.target.value),
				},
				vatRate,
			);
		});
	},

	changeGift(items, event) {
		const row = event.target.closest('tr');

		if (!row) {
			return items;
		}

		const index = Number(row.dataset.index);

		return items.map((item, i) => {
			if (i !== index) {
				return item;
			}

			return this.calculateItem(
				{
					...item,

					is_gift: event.target.checked ? 1 : 0,
				},
				item.vat_rate ?? 0,
			);
		});
	},

	calculateItem(item, vatRate) {
		if (item.is_gift) {
			return {
				...item,

				subtotal_amount: 0,

				vat_amount: 0,

				total_amount: 0,
			};
		}

		const subtotal_amount = Calculator.multiply(
			item.quantity,
			item.selling_price,
		);

		const vat_amount = Calculator.multiply(subtotal_amount, vatRate / 100);

		const total_amount = Calculator.add(subtotal_amount, vat_amount);

		return {
			...item,

			subtotal_amount,

			vat_amount,

			total_amount,
		};
	},

	removeItem(items, event) {
		const row = event.target.closest('tr');

		if (!row) {
			return items;
		}

		return items.filter((_, index) => index !== Number(row.dataset.index));
	},

	/* =================================================
	   PAYLOAD
	================================================= */

	payload(order = {}, summary = {}, items = []) {
		return {
			customer_id: order.customer_id ?? null,

			description: order.description ?? '',

			note: order.note ?? '',

			status: order.status ?? 'pending',

			payment: order.payment ?? 'unpaid',

			subtotal_amount: summary.subtotal_amount ?? 0,

			vat_rate: order.vat_rate ?? 0,

			vat_amount: summary.vat_amount ?? 0,

			total_amount: summary.total_amount ?? 0,

			paid_amount: order.paid_amount ?? 0,

			debt_amount: summary.debt_amount ?? 0,

			created_by: order.created_by ?? null,

			items,
		};
	},
};

export default Service;
