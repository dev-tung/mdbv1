import Dom from '../../../../helpers/dom.js';

import Table from '../../../../components/table.js';

import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Controller = {
	init() {
		Table.init({
			body: '#purchase-table-body',
			colspan: 10,

			filters: {
				'#filter-date-from': {
					handler(value) {
						State.filters.date_from = value;
					},
				},

				'#filter-date-to': {
					handler(value) {
						State.filters.date_to = value;
					},
				},

				'#filter-supplier': {
					event: 'input', // hoặc keyup
					handler(value) {
						console.log(value);
						State.filters.supplier = value.trim();
					},
				},

				'#filter-payment': {
					handler(value) {
						State.filters.payment = value;
					},
				},
			},

			async source() {
				const data = await Service.getList(State.filters);

				State.setDefault(data);
			},

			render: Renderer.renderTable,
		});
	}
};

export default Controller;

document.addEventListener('DOMContentLoaded', () => {
	Controller.init();
});