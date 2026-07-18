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
		Table.renderBody(State.suppliers, (supplier, index) => {
			const fragment = Dom.template('#supplier-row-template');

			const row = fragment.querySelector('tr');

			row.dataset.id = supplier.id;

			// =========================
			// TEXT
			// =========================

			const texts = {
				'.index': index + 1,

				'.supplier-name': supplier.name,

				'.phone': supplier.phone || '',

				'.email': supplier.email || '',

				'.address': supplier.address || '',

				'.created-at': supplier.created_at,
			};

			Object.entries(texts).forEach(([selector, value]) => {
				Dom.text(selector, value, row);
			});


			// =========================
			// EDIT
			// =========================

			row.querySelector('.edit-item').href =
				`/admin/suppliers/edit/${supplier.id}`;


			// =========================
			// DELETE
			// =========================

			row.querySelector('.delete-item').dataset.id = supplier.id;


			return fragment;
		});
	},

	/* =================================================
	   SUMMARY
	================================================= */

	renderSummary() {
		Dom.text(
			'#sum-total-supplier',
			State.summary.total,
		);
	},
};

export default Renderer;