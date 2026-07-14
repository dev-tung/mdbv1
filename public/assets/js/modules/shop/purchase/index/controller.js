import Dom from '../../../../helpers/dom.js';

import Table from '../../../../components/table.js';

import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Controller = {
	init() {
		this.bindTable();

		this.bindFilters();
	},

	bindTable() {
		Table.init({
			body: '#purchase-table-body',
			colspan: 10,

			async source(filters = State.filters) {
				const data = await Service.getList(filters);
				State.setDefault(data);
			},

			render: Renderer.renderTable,
		});
	},

	bindFilters() {
		Dom.find('#filter-date-from').addEventListener('change', async (e) => {
			State.filters.date_from = e.target.value;

			await Table.load(State.filters);
		});

		Dom.find('#filter-date-to').addEventListener('change', async (e) => {
			State.filters.date_to = e.target.value;

			await Table.load(State.filters);
		});

		Dom.find('#filter-supplier').addEventListener('change', async (e) => {
			State.filters.supplier_id = Number(e.target.value) || null;

			await Table.load(State.filters);
		});

		Dom.find('#filter-payment').addEventListener('change', async (e) => {
			State.filters.payment = e.target.value;

			await Table.load(State.filters);
		});
	},
};

export default Controller;

document.addEventListener('DOMContentLoaded', () => {
	Controller.init();
});