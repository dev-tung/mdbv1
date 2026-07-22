import State from './state.js';

import Dom from '../../../../helpers/dom.js';
import Formatter from '../../../../helpers/formatter.js';

import Option from '../../../../shared/option.js';

import Select from '../../../../components/select.js';
import Table from '../../../../components/table.js';
import Label from '../../../../components/label.js';

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
		Select.render('#filter-status', Option.product, State.filters.status, '-- Trạng thái --');

		Select.render('#filter-category', State.categories, State.filters.category_id, '-- Danh mục --');
	},

	/* =================================================
	   TABLE
	================================================= */

	renderTable() {
		const uploadPath = '/uploads/products/';
		const noImage = '/assets/image/no-image.svg';

		Table.renderBody(State.products, (product, index) => {
			const fragment = Dom.template('#product-row-template');

			const row = fragment.querySelector('tr');

			row.dataset.id = product.id;

			// =========================
			// THUMBNAIL
			// =========================

			const thumbnail = row.querySelector('.thumbnail');

			if (thumbnail) {
				thumbnail.src = product.thumbnail
					? product.thumbnail.startsWith('uploads/')
						? `/${product.thumbnail}`
						: `${uploadPath}${product.thumbnail}`
					: noImage;
			}

			// =========================
			// TEXT
			// =========================

			const texts = {
				'.index': index + 1,

				'.product-name': product.name,

				'.category-name': product.category_name || '',

				'.price': Formatter.money(product.price),

				'.sale-price': Formatter.money(product.sale_price || 0),

				'.created-at': product.created_at,
			};

			Object.entries(texts).forEach(([selector, value]) => {
				Dom.text(selector, value, row);
			});

			// =========================
			// STATUS
			// =========================

			Label.render(row.querySelector('.status'), Option.product, product.status);

			row.querySelector('.status').dataset.id = product.id;

			// =========================
			// EDIT
			// =========================

			row.querySelector('.edit-item').href = `/admin/products/edit/${product.id}`;

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
			'#sum-total-product',

			State.summary.total,
		);
	},
};

export default Renderer;
