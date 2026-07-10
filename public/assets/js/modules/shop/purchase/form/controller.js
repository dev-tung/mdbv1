import Service from './Service.js';

import Renderer from './Renderer.js';

import Notify from '../../../helpers/notify.js';

const Controller = {
	/* ===============================
	   SUPPLIER
	=============================== */

	async searchSupplier(keyword) {
		return await Service.searchSuppliers(keyword);
	},

	selectSupplier(item) {
		Service.setSupplier(item);

		Renderer.purchase();
	},

	/* ===============================
	   PRODUCT
	=============================== */

	async searchProduct(keyword) {
		return await Service.searchProducts(keyword);
	},

	selectProduct(item) {
		Service.addProduct(item);

		Renderer.products();

		Renderer.summary();
	},

	/* ===============================
	   PURCHASE
	=============================== */

	changeDescription(e) {
		Service.setDescription(e.target.value);
	},

	changeStatus(e) {
		Service.setStatus(e.target.value);
	},

	changeWarehouse(e) {
		Service.setWarehouse(e.target.value);
	},

	changePayment(e) {
		Service.setPayment(e.target.value);

		Renderer.payment();
	},

	changePaidAmount(e) {
		Service.setPaidAmount(e.target.value);

		Renderer.summary();
	},

	changeVatRate(e) {
		Service.setVatRate(e.target.value);

		Renderer.products();

		Renderer.summary();
	},

	/* ===============================
	   ITEMS
	=============================== */

	changeItem(e) {
		const row = e.target.closest('tr');

		if (!row) return;

		const index = Number(row.dataset.index);
		const value = e.target.value;

		const className = e.target.classList[0];

		switch (className) {
			case 'quantity':
				Service.setQuantity(index, value);
				break;

			case 'purchase-price':
				Service.setPurchasePrice(index, value);
				break;

			case 'selling_price':
				Service.setSellingPrice(index, value);
				break;
		}

		Renderer.amount(index);
		Renderer.summary();
	},

	removeItem(e) {
		const button = e.target.closest('.btn-remove');

		if (!button) return;

		const row = button.closest('tr');

		Service.removeProduct(Number(row.dataset.index));

		Renderer.products();

		Renderer.summary();

		Notify.success('Đã xóa sản phẩm');
	},

	/* ===============================
	   SUBMIT
	=============================== */

	async submit(e) {
		e.preventDefault();

		try {
			const response = await Service.save();

			if (response.success) {
				Notify.success(response.message);

				window.location.href = response.redirect;

				return;
			}

			Notify.error(response.message);
		} catch (error) {
			Notify.error(error.message);
		}
	},
};

export default Controller;
