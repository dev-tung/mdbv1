import Calculator from '../../../../helpers/calculator.js';
import Api from './api.js';

const Service = {
	async getDefault(id = null) {
		const [purchase, warehouses, suppliers, products] = await Promise.all([
			id ? Api.show(id) : null,
			Api.getWarehouses(),
		]);

		return {
			purchase: purchase?.purchase ?? {},
			items: purchase?.items ?? [],
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

					amount: Calculator.multiply(newItem.quantity, newItem.purchase_price),
				};
			});
		}

		// Sản phẩm mới

		const item = {
			product_id: product.id,

			code: product.code,

			name: product.name,

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
		const subtotal = Calculator.multiply(item.quantity, item.purchase_price);

		const tax = Calculator.multiply(subtotal, vatRate / 100);

		const total = Calculator.add(subtotal, tax);

		return {
			...item,
			subtotal,
			tax,
			total,
		};
	},
	removeItem(items, event) {
		if (!event.target.matches('.btn-remove-item')) {
			return items;
		}

		const row = event.target.closest('tr');

		if (!row) {
			return items;
		}

		return items.filter((_, index) => index !== Number(row.dataset.index));
	},
};

export default Service;
