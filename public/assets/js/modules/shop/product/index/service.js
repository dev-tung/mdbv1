import Api from './api.js';


const Service = {

	async getList(filters = {}) {

		const response = await Api.getProducts(filters);


		const categories = await Api.getCategories();



		const products = response.data[0] || [];


		const summary = response.data[1] || {};



		const page = Number(filters.page || 1);

		const per_page = Number(filters.per_page || 10);


		const total = Number(summary.total || 0);



		return {

			products,


			categories: categories.data || [],


			summary,


			pagination: {

				page,

				per_page,

				total,

				last_page: Math.ceil(total / per_page),

			},

		};

	},

};


export default Service;