import Api from './api.js';

const Service = {
	async getList(filters = {}) {
		const response = await Api.getList(filters);

		return {
			suppliers: response.suppliers,
			purchases: response.purchases,
			summary: response.summary,
			pagination: response.pagination,
		};
	},
};

export default Service;