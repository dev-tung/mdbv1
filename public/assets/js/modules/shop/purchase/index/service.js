import Api from './api.js';

const Service = {
	async getList(filters = {}) {
		const [purchases, suppliers] = await Promise.all([
			Api.getPurchases(filters),
			Api.getSuppliers(),
		]);

		return {
			suppliers: suppliers.data,
			purchases: purchases.data,
			summary: purchases.summary,
			pagination: purchases.pagination,
		};
	},
};

export default Service;