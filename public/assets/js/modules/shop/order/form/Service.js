import State from './State.js';
import Api from './Api.js';
import Renderer from './Renderer.js';

const Service = {
	/* =================================================
       LOAD ORDER
    ================================================= */

	async loadOrder(id) {
		const response = await Api.showOrder(id);

		if (!response.success) {
			throw new Error(response.message);
		}

		const [orders, items] = response.data;

		Object.assign(State.order, orders[0] || {});

		State.order.items = items || [];

		this.calculate();

		State.order.items.forEach((item) => {
			item.discount_amount = Number(item.discount_amount || 0);

			item.is_gift = Boolean(Number(item.is_gift));
		});

		this.calculate();
	},

	/* =================================================
       CUSTOMER SEARCH
    ================================================= */

	async searchCustomers() {
		const keyword = State.customer.keyword;

		if (!keyword) {
			State.customer.suggestions = [];

			Renderer.customerSuggestions();

			return;
		}

		const response = await Api.searchCustomers(keyword);

		State.customer.suggestions = response.data || [];

		Renderer.customerSuggestions();
	},

	/* =================================================
       PRODUCT SEARCH
    ================================================= */

	async searchProducts() {
		const keyword = State.product.keyword;

		if (!keyword) {
			State.product.suggestions = [];

			Renderer.productSuggestions();

			return;
		}

		const response = await Api.searchProducts(keyword);

		State.product.suggestions = response.data || [];

		Renderer.productSuggestions();
	},

	/* =================================================
       SET CUSTOMER
    ================================================= */

	setCustomer(customer) {
		State.order.customer_id = customer.id;

		State.order.customer_name = customer.name;
	},

	/* =================================================
       ADD PRODUCT
    ================================================= */

	addProduct(product) {
		const exists = State.order.items.find((item) => item.product_id == product.product_id);

		if (exists) {
			exists.quantity++;
		} else {
			State.order.items.push({
				product_id: product.product_id,

				purchase_id: product.purchase_id,

				product_name: product.product_name,

				quantity: 1,

				purchase_price: Number(product.purchase_price || 0),

				selling_price: Number(product.selling_price || 0),

				discount_amount: 0,

				is_gift: false,

				subtotal_amount: 0,

				vat_rate: Number(State.order.vat_rate || 0),

				vat_amount: 0,

				total_amount: 0,
			});
		}

		this.calculate();
	},

	setDiscountAmount(index, value) {
		State.order.items[index].discount_amount = Number(value || 0);

		this.calculate();
	},

	setGift(index, checked) {
		State.order.items[index].is_gift = checked;

		this.calculate();
	},

	/* =================================================
       SETTERS
    ================================================= */

	setDescription(value) {
		State.order.description = value;
	},

	setNote(value) {
		State.order.note = value;
	},

	setStatus(value) {
		State.order.status = value;
	},

	setPayment(value) {
		State.order.payment = value;

		this.calculateDebt();
	},

	setPaidAmount(value) {
		State.order.paid_amount = Number(value || 0);

		this.calculateDebt();
	},

	setVatRate(value) {
		State.order.vat_rate = Number(value || 0);

		State.order.items.forEach((item) => {
			item.vat_rate = Number(value || 0);
		});

		this.calculate();
	},

	setQuantity(index, value) {
		State.order.items[index].quantity = Number(value || 0);

		this.calculate();
	},

	setSellingPrice(index, value) {
		State.order.items[index].selling_price = Number(value || 0);

		this.calculate();
	},

	removeProduct(index) {
		State.order.items.splice(index, 1);

		this.calculate();
	},

	/* =================================================
       CALCULATE
    ================================================= */

	calculate() {
		let subtotal = 0;

		let vat = 0;

		let total = 0;

		let discount = 0;

		State.order.items.forEach((item) => {
			if (item.is_gift) {
				item.subtotal_amount = 0;
				item.discount_amount = 0;
				item.vat_amount = 0;
				item.total_amount = 0;
			} else {
				const amount = Number(item.quantity || 0) * Number(item.selling_price || 0);

				item.subtotal_amount = amount;

				item.discount_amount = Number(item.discount_amount || 0);

				if (item.discount_amount > item.subtotal_amount) {
					item.discount_amount = item.subtotal_amount;
				}

				const taxable = item.subtotal_amount - item.discount_amount;

				item.vat_amount = (taxable * Number(item.vat_rate || 0)) / 100;

				item.total_amount = taxable + item.vat_amount;
			}

			subtotal += item.subtotal_amount;

			discount += item.discount_amount;

			vat += item.vat_amount;

			total += item.total_amount;
		});

		State.order.subtotal_amount = subtotal;

		State.order.discount_amount = discount;

		State.order.vat_amount = vat;

		State.order.total_amount = total;

		this.calculateDebt();
	},

	/* =================================================
       DEBT
    ================================================= */

	calculateDebt() {
		State.order.debt_amount = Number(State.order.total_amount || 0) - Number(State.order.paid_amount || 0);

		if (State.order.debt_amount < 0) {
			State.order.debt_amount = 0;
		}
	},

	/* =================================================
       SAVE
    ================================================= */

	async save() {
		this.calculate();

		let response;

		if (State.order.id) {
			response = await Api.updateOrder(
				State.order.id,

				State.order,
			);
		} else {
			response = await Api.createOrder(State.order);
		}

		return response;
	},
};

export default Service;
