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
		Table.renderBody(State.employees, (employee, index) => {
			const fragment = Dom.template('#employee-row-template');

			const row = fragment.querySelector('tr');

			row.dataset.id = employee.id;

			// =========================
			// TEXT
			// =========================

			const texts = {
				'.index': index + 1,

				'.employee-name': employee.name,

				'.phone': employee.phone || '',

				'.email': employee.email || '',

				'.address': employee.address || '',

				'.created-at': employee.created_at,
			};

			Object.entries(texts).forEach(([selector, value]) => {
				Dom.text(selector, value, row);
			});

			// =========================
			// EDIT
			// =========================

			row.querySelector('.edit-item').href = `/admin/employees/edit/${employee.id}`;

			// =========================
			// DELETE
			// =========================

			row.querySelector('.delete-item').dataset.id = employee.id;

			return fragment;
		});
	},

	/* =================================================
	   SUMMARY
	================================================= */

	renderSummary() {
		Dom.text('#sum-total-employee', State.summary.total);
	},
};

export default Renderer;
