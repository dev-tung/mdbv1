import Service from './service.js';

import Renderer from './renderer.js';

import Notify from '../../../helpers/notify.js';

const Controller = {
	/* ===============================
	   INIT
	=============================== */

	async init() {
		try {
			await Promise.all([Service.loadSuppliers(), Service.loadPurchases()]);

			Renderer.render();
		} catch (error) {
			Notify.error(error.message);
		}
	},

	/* ===============================
	   FILTER
	=============================== */

	async changeDateFrom(e) {
		Service.setDateFrom(e.target.value);

		await this.reload();
	},

	async changeDateTo(e) {
		Service.setDateTo(e.target.value);

		await this.reload();
	},

	async changeSupplier(e) {
		Service.setSupplier(e.target.value);

		await this.reload();
	},

	async changePayment(e) {
		Service.setPayment(e.target.value);

		await this.reload();
	},

	/* ===============================
	   TABLE
	=============================== */

	async changeTable(e) {
		const target = e.target;

		const id = Number(target.dataset.id);

		if (!id) return;

		try {
			if (target.classList.contains('purchase-status')) {
				const response = await Service.updateStatus(id, target.value);

				Notify.success(response.message);
			}

			if (target.classList.contains('purchase-payment')) {
				const response = await Service.updatePayment(id, target.value);

				Notify.success(response.message);
			}

			await this.reload();
		} catch (error) {
			Notify.error(error.message);

			await this.reload();
		}
	},

	async clickTable(e) {
		const button = e.target.closest('.btn-delete-purchase');

		if (!button) return;

		if (!confirm('Bạn có chắc muốn xóa phiếu nhập này?')) {
			return;
		}

		try {
			button.disabled = true;

			const response = await Service.delete(Number(button.dataset.id));

			Notify.success(response.message);

			await this.reload();
		} catch (error) {
			Notify.error(error.message);
		} finally {
			button.disabled = false;
		}
	},

	/* ===============================
	   PAGINATION
	=============================== */

	async changePage(e) {
		const button = e.target.closest('[data-page]');

		if (!button) return;

		Service.setPage(Number(button.dataset.page));

		await this.reload();
	},

	/* ===============================
	   RELOAD
	=============================== */

	async reload() {
		try {
			await Service.loadPurchases();

			Renderer.render();
		} catch (error) {
			Notify.error(error.message);
		}
	},
};

export default Controller;
