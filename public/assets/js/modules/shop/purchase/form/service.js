import Calculator from '../../../../helpers/calculator.js';

const Service = {
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

	changeItem(items, index, field, value) {
		return items.map((item, i) => {
			if (i !== index) {
				return item;
			}

			const newItem = {
				...item,

				[field]: Number(value),
			};

			return {
				...newItem,

				amount: Calculator.multiply(newItem.quantity, newItem.purchase_price),
			};
		});
	},

	removeItem(items, index) {
		return items.filter((_, i) => i !== index);
	},
};

export default Service;
