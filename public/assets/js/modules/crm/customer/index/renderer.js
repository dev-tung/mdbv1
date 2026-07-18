import State from './state.js';

import Dom from '../../../../helpers/dom.js';

import Table from '../../../../components/table.js';

const Renderer = {
	/* =================================================
	   PUBLIC
	================================================= */

	render() {
		this.renderTable();

		this.renderSummary();
	},

	/* =================================================
	   TABLE
	================================================= */

	renderTable() {
		Table.renderBody(State.customers, (customer, index) => {
			const fragment = Dom.template('#customer-row-template');

			const row = fragment.querySelector('tr');

			row.dataset.id = customer.id;

			// =========================
			// TEXT
			// =========================

			const texts = {
				'.index': index + 1,

				'.customer-name': customer.name,

				'.phone': customer.phone || '',

				'.email': customer.email || '',

				'.address': customer.address || '',

				'.created-at': customer.created_at,
			};

			Object.entries(texts).forEach(([selector, value]) => {
				Dom.text(selector, value, row);
			});

			// =========================
			// EDIT
			// =========================

			row.querySelector('.edit-item').href = `/admin/customers/edit/${customer.id}`;

			// =========================
			// DELETE
			// =========================

			row.querySelector('.delete-item').dataset.id = customer.id;

			return fragment;
		});
	},

	/* =================================================
	   SUMMARY
	================================================= */

	renderSummary() {
		Dom.text('#sum-total-customer', State.summary.total);
	},
};

export default Renderer;
