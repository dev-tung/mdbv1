import Dom from '../../../../helpers/dom.js';

import Table from '../../../../components/table.js';

import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Controller = {
  
	async init() {
		Table.init({
			body: '#purchase-table-body',
			pagination: '#purchase-pagination',
			colspan: 10,

			render: Renderer.renderTable.bind(Renderer),

			onPage: this.changePage.bind(this),
		});

		await this.load();

		Renderer.render();

		this.bindFilters();
	},

	async load(page = 1) {
		State.filters.page = page;

		const data = await Service.getList(State.filters);

		State.setDefault(data);
	},

	/* =================================================
       FILTERS
    ================================================= */

	bindFilters() {
		Dom.find('#filter-date-from').addEventListener('change', async (e) => {
			State.filters.date_from = e.target.value;

			await this.search();
		});

		Dom.find('#filter-date-to').addEventListener('change', async (e) => {
			State.filters.date_to = e.target.value;

			await this.search();
		});

		Dom.find('#filter-supplier').addEventListener('change', async (e) => {
			State.filters.supplier_id = Number(e.target.value);

			await this.search();
		});

		Dom.find('#filter-payment').addEventListener('change', async (e) => {
			State.filters.payment = e.target.value;

			await this.search();
		});
	},

	async search() {
		State.filters.page = 1;

		await this.load();

		Renderer.render();
	},

	async changePage(page) {
		await this.load(page);

		Renderer.render();
	},
};

export default Controller;

document.addEventListener('DOMContentLoaded', () => {
	Controller.init();
});