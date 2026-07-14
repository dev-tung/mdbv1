import Dom from '../../../../helpers/dom.js';
import Api from './api.js';
import Table from '../../../../components/table.js';

import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Controller = {
	init() {
		Table.init({
			body: '#purchase-table-body',

			pagination: true,

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
					event: 'input',

					handler(value) {
						State.filters.supplier = value.trim();
					},
				},

				'#filter-payment': {
					handler(value) {
						State.filters.payment = value;
					},
				},
			},

			async source({ page, per_page }) {
				const data = await Service.getList({
					...State.filters,

					page,

					per_page,
				});

				State.setDefault(data);

				return data;
			},

			render: Renderer.renderTable,
		});

		this.bindEvents();
	},

	bindEvents() {
		Dom.find('#purchase-table-body').addEventListener('change', async (e) => {
			const target = e.target;

			try {
				if (target.classList.contains('status')) {
					const id = target.dataset.id;

					const status = target.value;

					const response = await Api.updatePurchaseStatus(id, status);

					alert(response.message);
				}

				if (target.classList.contains('payment')) {
					const id = target.dataset.id;

					const payment = target.value;

					const response = await Api.updatePurchasePayment(id, payment);

					alert(response.message);
				}

				const data = await Service.getList({
					...State.filters,

					page: Table.config.page,

					per_page: Table.config.per_page,
				});

				State.setDefault(data);

				Renderer.render();
			} catch (error) {
				alert(error.message);
			}
		});
	},
};

export default Controller;

document.addEventListener('DOMContentLoaded', () => {
	Controller.init();
});
