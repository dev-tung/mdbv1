import State from './state.js';

import Api from './api.js';

const Service = {
	/* ===============================
	   LOAD
	=============================== */

	async load() {
		const warehouses = await Api.getWarehouses();

		State.warehouse.list = warehouses.data ?? [];
	},

	async loadPurchase(id) {
		const response = await Api.showPurchase(id);

		State.purchase = response.data ?? State.purchase;
	},

	/* ===============================
	   SUPPLIER
	=============================== */

	async searchSuppliers(keyword) {
		State.supplier.keyword = keyword;

		const response = await Api.searchSuppliers(keyword);

		State.supplier.suggestions = response.data ?? [];

		return State.supplier.suggestions;
	},

	setSupplier(item) {
		State.purchase.supplier_id = item.id;

		State.purchase.supplier_name = item.name;
	},

	/* ===============================
	   PRODUCT
	=============================== */

	async searchProducts(keyword) {
		State.product.keyword = keyword;

		const response = await Api.searchProducts(keyword);

		State.product.suggestions = response.data ?? [];

		return State.product.suggestions;
	},

	addProduct(product) {
		const exists = State.purchase.items.find((item) => item.product_id == product.id);

		if (exists) {
			exists.quantity++;

			this.calculateItem(exists);

			return;
		}

		State.purchase.items.push({
			product_id: product.id,

			product_name: product.name,

			quantity: 1,

			purchase_price: 0,

			selling_price: 0,

			subtotal_amount: 0,

			vat_amount: 0,

			total_amount_with_vat: 0,
		});
	},

	removeProduct(index) {
		State.purchase.items.splice(index, 1);

		this.calculate();
	},

	/* ===============================
	   PURCHASE
	=============================== */

	setDescription(value) {
		State.purchase.description = value;
	},

	setStatus(value) {
		State.purchase.status = value;
	},

	setWarehouse(value) {
		State.purchase.warehouse_id = value;
	},

	setPayment(value) {
		State.purchase.payment = value;

		this.calculate();
	},

	setPaidAmount(value) {
		State.purchase.paid_amount = Number(value || 0);

		this.calculate();
	},

	setVatRate(value) {
		State.purchase.vat_rate = Number(value || 0);

		this.calculate();
	},

	/* ===============================
	   ITEMS
	=============================== */

	setQuantity(index, value) {
		const item = State.purchase.items[index];

		if (!item) return;

		item.quantity = Number(value || 0);

		this.calculateItem(item);
	},

	setPurchasePrice(index, value) {
		const item = State.purchase.items[index];

		if (!item) return;

		item.purchase_price = Number(value || 0);

		this.calculateItem(item);
	},

	setSellingPrice(index, value) {
		const item = State.purchase.items[index];

		if (!item) return;

		item.selling_price = Number(value || 0);
	},

	/* ===============================
	   CALCULATE
	=============================== */

	calculateItem(item) {
		const purchase = State.purchase;

		item.subtotal_amount = Calculator.multiply(item.quantity, item.purchase_price);

		item.vat_amount = Calculator.percent(item.subtotal_amount, purchase.vat_rate);

		item.total_amount_with_vat = Calculator.add(item.subtotal_amount, item.vat_amount);

		this.calculate();
	},

	calculate() {
		const purchase = State.purchase;

		const items = purchase.items;

		purchase.subtotal_amount = Calculator.sum(items, 'subtotal_amount');

		purchase.vat_amount = Calculator.sum(items, 'vat_amount');

		purchase.total_amount = Calculator.add(purchase.subtotal_amount, purchase.vat_amount);

		purchase.debt_amount = Calculator.subtract(purchase.total_amount, purchase.paid_amount);
	},

	/* ===============================
	   SAVE
	=============================== */

	async save() {
		this.calculate();

		const id = State.purchase.id;

		if (id) {
			return await Api.updatePurchase(id, State.purchase);
		}

		return await Api.createPurchase(State.purchase);
	},

	async delete(id) {
		return await Api.deletePurchase(id);
	},
};

export default Service;
