const State = {
	order: {
		id: 1,

		customer_id: 2,

		customer_name: 'Nguyễn Văn A',

		description: 'Đơn hàng tháng 07',

		status: 'pending',

		vat_rate: 8,

		payment: 'partial',

		paid_amount: 5000000,
	},

	items: [
		{
			id: 1,

			product_name: 'Yonex Astrox 100ZZ',

			quantity: 2,

			selling_price: 4900000,

			vat_rate: 8,

			is_gift: 0,

			subtotal_amount: 9800000,

			vat_amount: 784000,

			total_amount: 10584000,
		},

		{
			id: 2,

			product_name: 'Lining Axforce 90',

			quantity: 3,

			selling_price: 3600000,

			vat_rate: 8,

			is_gift: 1,

			subtotal_amount: 0,

			vat_amount: 0,

			total_amount: 0,
		},
	],

	summary: {
		subtotal_amount: 9800000,

		vat_amount: 784000,

		total_amount: 10584000,

		debt_amount: 5584000,
	},

	/* =================================================
	   CUSTOMER
	================================================= */

	setCustomer(customer) {
		this.order.customer_id = customer.id;

		this.order.customer_name = customer.name;
	},

	/* =================================================
	   DEFAULT
	================================================= */

	setDefault(data) {
		this.order = data.order;

		this.items = data.items;
	},

	/* =================================================
	   SUMMARY
	================================================= */

	setSummary() {
		const subtotal_amount = this.items.reduce(
			(sum, item) => sum + Number(item.subtotal_amount || 0),

			0,
		);

		const vat_amount = this.items.reduce(
			(sum, item) => sum + Number(item.vat_amount || 0),

			0,
		);

		const total_amount = this.items.reduce(
			(sum, item) => sum + Number(item.total_amount || 0),

			0,
		);

		const debt_amount = total_amount - Number(this.order.paid_amount || 0);

		this.summary = {
			subtotal_amount,

			vat_amount,

			total_amount,

			debt_amount,
		};
	},
};

export default State;
