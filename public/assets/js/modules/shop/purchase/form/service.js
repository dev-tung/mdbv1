const Service = {
	/* =================================================
       PRODUCT
    ================================================= */

	selectProduct(items, product) {
		const index = items.findIndex((item) => item.product_id === product.id);

		if (index !== -1) {
			const newItems = [...items];

			newItems[index] = {
				...newItems[index],
				quantity: newItems[index].quantity + 1,
			};

			return newItems;
		}

		return [
			...items,

			{
				product_id: product.id,
				code: product.code,
				name: product.name,

				quantity: 1,

				purchase_price: product.purchase_price ?? 0,
				selling_price: product.selling_price ?? 0,
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

			return {
				...item,
				[field]: Number(value),
			};
		});
	},

	removeItem(items, index) {
		return items.filter((_, i) => i !== index);
	},
};

export default Service;
