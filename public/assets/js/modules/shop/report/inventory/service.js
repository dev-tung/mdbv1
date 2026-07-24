import Api from './api.js';
import State from './state.js';

const Service = {
	async load() {
		const response = await Api.getExports(State.filter);

		if (!response.success) {
			State.exports = [];
			State.summary = {
				total_quantity: 0,
				total_revenue: 0,
				total_profit: 0,
			};

			return;
		}

		State.exports = response.data.details;

		State.summary = response.data.summary ?? this.calculateSummary(State.exports);
	},

	calculateSummary(items) {
		return items.reduce(
			(summary, item) => {
				summary.total_quantity += Number(item.quantity);

				summary.total_revenue += Number(item.revenue);

				summary.total_profit += Number(item.profit);

				return summary;
			},
			{
				total_quantity: 0,
				total_revenue: 0,
				total_profit: 0,
			},
		);
	},

	filterItems() {
		return State.exports.filter((item) =>
			item.product_name.toLowerCase().includes(State.filter.keyword.toLowerCase()),
		);
	},

	getPageItems(items) {
		const start = (State.page - 1) * State.itemsPerPage;

		return items.slice(start, start + State.itemsPerPage);
	},
};

export default Service;
