import State from './state.js';

import Dom from '../../../../helpers/dom.js';
import Formatter from '../../../../helpers/formatter.js';

import Option from '../../../../shared/option.js';

import Select from '../../../../components/select.js';
import Table from '../../../../components/table.js';


const Renderer = {


	/* =================================================
	   PUBLIC
	================================================= */

	render() {

		this.renderOptions();

		this.renderTable();

		this.renderSummary();

	},



	/* =================================================
	   OPTIONS
	================================================= */

	renderOptions() {


		Select.render(
			'#filter-status',
			Option.product_status,
			State.filters.status,
			'-- Trạng thái --',
		);



		Select.render(
			'#filter-category',
			State.categories,
			State.filters.category_id,
			'-- Danh mục --',
		);


	},



	/* =================================================
	   TABLE
	================================================= */

	renderTable() {


		Table.renderBody(State.products, (product, index) => {


			const fragment = Dom.template('#product-row-template');


			const row = fragment.querySelector('tr');



			row.dataset.id = product.id;



			// =========================
			// TEXT
			// =========================

			const texts = {


				'.index': index + 1,


				'.product-name': product.name,


				'.category-name': product.category_name || '',



				'.price': Formatter.money(
					product.price
				),



				'.sale-price': Formatter.money(
					product.sale_price || 0
				),



				'.created-at': product.created_at,


			};



			Object.entries(texts).forEach(([selector, value]) => {


				Dom.text(
					selector,
					value,
					row
				);


			});




			// =========================
			// STATUS
			// =========================

			Select.render(

				row.querySelector('.status'),

				Option.product_status,

				product.status,

			);



			row.querySelector('.status').dataset.id = product.id;




			// =========================
			// EDIT
			// =========================

			const edit = row.querySelector('.edit-item');


			edit.href = `/admin/products/edit/${product.id}`;





			// =========================
			// DELETE
			// =========================

			row.querySelector('.delete-item').dataset.id = product.id;



			return fragment;


		});


	},




	/* =================================================
	   SUMMARY
	================================================= */

	renderSummary() {


		Dom.text(

			'#total-amount',

			State.summary.total

		);


	},


};


export default Renderer;