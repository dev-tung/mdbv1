import Calculator from '../../../../helpers/calculator.js';
import Api from './api.js';
import State from './state.js';

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
		const index = items.findIndex(
			(item) =>
				item.purchase_id === product.purchase_id &&
				item.product_id === product.product_id,
		);

		// Đã có trong danh sách

		if (index !== -1) {
			return items.map((item, i) => {
				if (i !== index) {
					return item;
				}

				const quantity = Math.min(
					item.quantity + 1,
					item.stock_quantity,
				);

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

			// Tồn kho
			stock_quantity: Number(product.quantity ?? 0),

			// Số lượng bán
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

	async changeQuantity(event) {
		const inputQuantity = getInputQuantity();

		const { index, item } = getItem();

		const maxQuantity = await getMaxQuantity(item);

		// Validate Quantity

		if (inputQuantity > maxQuantity) {
			return {
				success: false,
				message: `Số lượng tồn chỉ còn ${maxQuantity}.`,
			};
		}

		return {
			success: true,
			index,
			item: this.calculateItem(
				{
					...item,
					quantity: inputQuantity,
				},
				State.order.vat_rate,
			),
		};

		// Get Input Quantity

		function getInputQuantity() {
			return Number(event.target.value);
		}

		// Get Item

		function getItem() {
			const row = event.target.closest('tr');

			const index = Number(row.dataset.index);

			return {
				index,
				item: State.items[index],
			};
		}

		// Get Max Quantity

		async function getMaxQuantity(item) {
			const response = await Api.getQuantity(
				item.product_id,
				item.purchase_id,
			);

			const stockQuantity = Number(response.data?.quantity ?? 0);

			if (State.order.id) {
				return stockQuantity + Number(item.original_quantity ?? 0);
			}

			return stockQuantity;
		}
	},

	changePrice(event) {
		const { index, item } = getItem();

		return {
			success: true,
			index,
			item: this.calculateItem(
				{
					...item,
					selling_price: Number(event.target.value),
				},
				State.order.vat_rate,
			),
		};

		// Get Item

		function getItem() {
			const row = event.target.closest('tr');

			const index = Number(row.dataset.index);

			return {
				index,
				item: State.items[index],
			};
		}
	},

	changeGift(event) {
		const { index, item } = getItem();

		return {
			success: true,
			index,
			item: this.calculateItem(
				{
					...item,
					is_gift: event.target.checked ? 1 : 0,
				},
				State.order.vat_rate,
			),
		};

		// Get Item

		function getItem() {
			const row = event.target.closest('tr');

			const index = Number(row.dataset.index);

			return {
				index,
				item: State.items[index],
			};
		}
	},

	removeItem(event) {
		const items = getItems();

		return {
			success: true,
			items,
		};

		// Get Items

		function getItems() {
			const row = event.target.closest('tr');

			const index = Number(row.dataset.index);

			return State.items.filter((_, i) => i !== index);
		}
	},

	/* =================================================
	   CALCULATE
	================================================= */

	calculateItem(item, vatRate) {
		if (Number(item.is_gift) === 1) {
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