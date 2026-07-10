import State from './state.js';

import Api from './api.js';

import Calculator from '../../../helpers/calculator.js';

const Service = {
	/* ===============================
	   FILTER
	=============================== */

	setDateFrom(value) {
		State.filter.date_from = value;
		State.filter.page = 1;
	},

	setDateTo(value) {
		State.filter.date_to = value;
		State.filter.page = 1;
	},

	setSupplier(value) {
		State.filter.supplier_id = value;
		State.filter.page = 1;
	},

	setPayment(value) {
		State.filter.payment = value;
		State.filter.page = 1;
	},

	setPage(value) {
		State.filter.page = value;
	},

	/* ===============================
	   LOAD
	=============================== */

	async loadPurchases() {
		const response = await Api.getPurchases(State.filter);

		if (!response.success) {
			throw new Error(response.message);
		}

		const purchases = response.data?.data ?? response.data ?? [];

		State.purchases = purchases;

		State.summary = Calculator.purchaseSummary(purchases);

		State.pagination = response.data?.pagination ?? {
			current_page: 1,
			last_page: 1,
			per_page: purchases.length,
			total: purchases.length,
		};

		return purchases;
	},

	async loadSuppliers() {
		const response = await Api.getSuppliers();

		if (!response.success) {
			throw new Error(response.message);
		}

		State.suppliers = response.data ?? [];

		return State.suppliers;
	},

	/* ===============================
	   TABLE
	=============================== */

	async updateStatus(id, status) {
		const response = await Api.updateStatus(id, status);

		if (!response.success) {
			throw new Error(response.message);
		}

		return response;
	},

	async updatePayment(id, payment) {
		const response = await Api.updatePayment(id, payment);

		if (!response.success) {
			throw new Error(response.message);
		}

		return response;
	},

	async delete(id) {
		const response = await Api.delete(id);

		if (!response.success) {
			throw new Error(response.message);
		}

		return response;
	},
};

export default Service;
