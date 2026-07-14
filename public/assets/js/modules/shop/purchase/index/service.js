import Api from './api.js';

const Service = {
	async getList(filters = {}) {
		const [purchases] = await Promise.all([
			Api.getPurchases(filters)
		]);

		return {
			purchases: purchases.data,
			summary: purchases.summary,
			pagination: purchases.pagination,
		};
	},
};

export default Service;