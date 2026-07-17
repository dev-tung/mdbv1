import Dom from '../../../../helpers/dom.js';
import Api from './api.js';
import Table from '../../../../components/table.js';

import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';



const Controller = {


	init() {


		Table.init({


			body: '#product-table-body',



			pagination: true,



			colspan: 8,



			filters: {



				'#filter-name': {


					event: 'input',


					handler(value) {


						State.filters.keyword = value.trim();


					},


				},




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




				'#filter-status': {


					handler(value) {


						State.filters.status = value;


					},


				},




				'#filter-category': {


					handler(value) {


						State.filters.category_id = value;


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



				Renderer.renderOptions();



				Renderer.renderSummary();



				return data;


			},




			render: Renderer.renderTable,



		});



		this.bindEvents();



	},







	bindEvents() {



		const table = Dom.find('#product-table-body');





		// =========================
		// CHANGE
		// =========================

		table.addEventListener('change', async (e) => {



			const target = e.target;



			try {



				if (target.classList.contains('status')) {



					const response = await Api.updateProductStatus(


						target.dataset.id,


						target.value,


					);



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








		// =========================
		// DELETE
		// =========================

		table.addEventListener('click', async (e) => {



			const button = e.target.closest('.delete-item');



			if (!button) {


				return;


			}




			if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {


				return;


			}




			try {



				const response = await Api.deleteProduct(


					button.dataset.id


				);



				alert(response.message);





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