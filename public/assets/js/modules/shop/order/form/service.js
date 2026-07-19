import Calculator from '../../../../helpers/calculator.js';
import Api from './api.js';

const Service = {
	/* =================================================
	   DEFAULT
	================================================= */

	async getDefault(orderId = null) {
		const response = orderId ? await Api.showOrder(orderId) : null;

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
		const index = items.findIndex((item) => item.purchase_id === product.purchase_id);

		// Đã có trong danh sách

		if (index !== -1) {
			return items.map((item, i) => {
				if (i !== index) {
					return item;
				}

				const quantity = Math.min(item.quantity + 1, item.stock_quantity);

				return this.calculateItem(
					{
						...item,
						quantity,
					},
					item.vat_rate,
				);
			});
		}

		// Thêm mới

		const item = {
			product_id: product.product_id,
			purchase_id: product.purchase_id,
			product_name: product.product_name,

			// tồn kho
			stock_quantity: Number(product.quantity ?? 0),

			// số lượng bán
			quantity: 1,

			selling_price: Number(product.selling_price ?? 0),

			vat_rate: 0,

			is_gift: 0,
		};

		return [...items, this.calculateItem(item, item.vat_rate)];
	},

	/* =================================================
	   ITEM
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

			let value = Number(event.target.value);

			if (field === 'quantity') {
				value = Math.max(1, value);
			}

			return this.calculateItem(
				{
					...item,
					[field]: value,
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
				item.vat_rate,
			);
		});
	},

	removeItem(items, event) {
		const row = event.target.closest('tr');

		if (!row) {
			return items;
		}

		return items.filter((_, index) => index !== Number(row.dataset.index));
	},

	/* =================================================
	   CALCULATE
	================================================= */

	calculateItem(item, vatRate) {
		if (item.is_gift) {
			return {
				...item,
				vat_rate: vatRate,

				subtotal_amount: 0,
				vat_amount: 0,
				total_amount: 0,
			};
		}

		const subtotal_amount = Calculator.multiply(
			item.quantity,
			item.selling_price,
		);

		const vat_amount = Calculator.multiply(
			subtotal_amount,
			vatRate / 100,
		);

		const total_amount = Calculator.add(
			subtotal_amount,
			vat_amount,
		);

		return {
			...item,
			vat_rate: vatRate,

			subtotal_amount,
			vat_amount,
			total_amount,
		};
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