import Api from './api.js';

const Service = {
	/* =================================================
	   LIST
	================================================= */

	async getList(filters = {}) {
		const suppliersResponse = await Api.getSuppliers(filters);

		const suppliers = suppliersResponse.data[0];

		const summary = suppliersResponse.data[1][0];

		const total = Number(summary.total);

		const per_page = Number(filters.per_page ?? 10);

		return {
			suppliers,

			summary,

			pagination: {
				page: Number(filters.page ?? 1),

				per_page,

				total,

				last_page: Math.ceil(total / per_page),
			},
		};
	},
};

export default Service;
