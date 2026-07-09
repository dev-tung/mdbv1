import State from './State.js';
import Service from './Service.js';
import Renderer from './Renderer.js';

const Event = {
	init() {
		this.bindFilters();
		this.bindTable();
	},

	/* =========================
       FILTER
    ========================= */

	bindFilters() {
		const filters = ['date-from', 'date-to', 'supplier', 'payment'];

		filters.forEach((type) => {
			document.querySelector(`#filter-${type}`)?.addEventListener('change', async (e) => {
				const key = type.replace('-', '_');

				State.filter[key] = e.target.value;

				State.filter.page = 1;

				await this.reload();
			});
		});
	},

	/* =========================
       TABLE
    ========================= */

	bindTable() {
		const table = document.querySelector('#purchase-table-body');

		if (!table) return;

		table.addEventListener('change', async (e) => {
			const id = Number(e.target.dataset.id);

			if (!id) return;

			try {
				let response = null;

				if (e.target.classList.contains('purchase-status')) {
					response = await Service.status(id, e.target.value);
				}

				if (e.target.classList.contains('purchase-payment')) {
					response = await Service.payment(id, e.target.value);
				}

				if (response) {
					alert(response.message);
					await this.reload();
				}
			} catch (error) {
				alert(error.message);

				await this.reload();
			}
		});

		table.onclick = async (e) => {
			const button = e.target.closest('.btn-delete-purchase');

			if (!button) return;

			const id = Number(button.dataset.id);

			if (!confirm('Bạn có chắc muốn xóa phiếu nhập này?')) return;

			try {
				button.disabled = true;

				const response = await Service.deletePurchase(id);

				alert(response.message);

				if (response.success) {
					await this.reload();
				}
			} catch (error) {
				console.error(error);

				alert(error.message);
			} finally {
				button.disabled = false;
			}
		};
	},

	/* =========================
       RELOAD
    ========================= */

	async reload() {
		await Service.loadPurchases();

		Renderer.render();
	},
};

export default Event;
