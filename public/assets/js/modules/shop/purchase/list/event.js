import Controller from './controller.js';

const Event = {
	bind() {
		this.filters();

		this.table();

		this.pagination();
	},

	/* ===============================
	   FILTERS
	=============================== */

	filters() {
		const events = {
			'#filter-date-from': ['change', Controller.changeDateFrom],

			'#filter-date-to': ['change', Controller.changeDateTo],

			'#filter-supplier': ['change', Controller.changeSupplier],

			'#filter-payment': ['change', Controller.changePayment],
		};

		Object.entries(events).forEach(([selector, [event, handler]]) => {
			document.querySelector(selector)?.addEventListener(event, handler);
		});
	},

	/* ===============================
	   TABLE
	=============================== */

	table() {
		const table = document.querySelector('#purchase-table-body');

		if (!table) return;

		table.addEventListener('change', Controller.changeTable);

		table.addEventListener('click', Controller.clickTable);
	},

	/* ===============================
	   PAGINATION
	=============================== */

	pagination() {
		document.addEventListener('click', Controller.changePage);
	},
};

export default Event;
