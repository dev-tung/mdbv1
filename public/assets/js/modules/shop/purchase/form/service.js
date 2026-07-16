import Calculator from '../../../../helpers/calculator.js';
import Api from './api.js';

const Service = {
	async getDefault(purchase_id = null) {
		const [response, warehouses] = await Promise.all([
			purchase_id ? Api.showPurchase(purchase_id) : null,
			Api.getWarehouses(),
		]);

		const [purchase = [], items = []] = response?.data ?? [];

		return {
			purchase: purchase[0] ?? {},
			items,
			warehouses,
		};
	},

	/* =================================================
       PRODUCT
    ================================================= */

	selectProduct(items, product) {
		const index = items.findIndex((item) => item.product_id === product.id);

		// Nếu sản phẩm đã tồn tại

		if (index !== -1) {
			return items.map((item, i) => {
				if (i !== index) {
					return item;
				}

				const newItem = {
					...item,

					quantity: item.quantity + 1,
				};

				return {
					...newItem,

					amount: Calculator.multiply(
						newItem.quantity,
						newItem.purchase_price,
					),
				};
			});
		}

		// Sản phẩm mới

		const item = {
			product_id: product.id,

			code: product.code,

			product_name: product.name,

			quantity: 1,

			purchase_price: product.purchase_price ?? 0,

			selling_price: product.selling_price ?? 0,
		};

		return [
			...items,

			{
				...item,

				amount: Calculator.multiply(item.quantity, item.purchase_price),
			},
		];
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

		if (event.target.classList.contains('purchase-price')) {
			field = 'purchase_price';
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

	calculateItem(item, vatRate) {
		const subtotal_amount = Calculator.multiply(
			item.quantity,
			item.purchase_price,
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
	payload(purchase = {}, summary = {}, items = []) {
		return {
			supplier_id: purchase.supplier_id ?? null,
			warehouse_id: purchase.warehouse_id ?? null,

			description: purchase.description ?? '',
			note: purchase.note ?? '',

			status: purchase.status ?? 'pending',
			payment: purchase.payment ?? 'unpaid',

			subtotal_amount: summary.subtotal_amount ?? 0,
			vat_rate: purchase.vat_rate ?? 0,
			vat_amount: summary.vat_amount ?? 0,
			total_amount: summary.total_amount ?? 0,

			paid_amount: purchase.paid_amount ?? 0,
			debt_amount: summary.debt_amount ?? 0,

			created_by: purchase.created_by ?? null,

			items,
		};
	},
};

export default Service;
