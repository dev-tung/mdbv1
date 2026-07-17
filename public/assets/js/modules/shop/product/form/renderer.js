import State from './state.js';

import Dom from '../../../../helpers/dom.js';

import Option from '../../../../shared/option.js';

import Select from '../../../../components/select.js';

const Renderer = {
	/* =================================================
	   PUBLIC
	================================================= */

	render() {
		this.renderOptions();

		this.renderProduct();
	},

	/* =================================================
	   OPTIONS
	================================================= */

	renderOptions() {
		Select.render(
			'#category_id',
			State.options.categories.map((item) => ({
				value: item.id,
				label: item.name,
			})),
			State.form.category_id,
			'-- Chọn danh mục --',
		);

		Select.render(
			'#brand_id',
			State.options.brands.map((item) => ({
				value: item.id,
				label: item.name,
			})),
			State.form.brand_id,
			'-- Chọn thương hiệu --',
		);

		Select.render('#status', Option.product, State.form.status, '-- Chọn trạng thái --');
	},

	/* =================================================
	   PRODUCT
	================================================= */

	renderProduct() {
		const product = State.form;

		Dom.value('#name', product.name);

		Dom.value('#price', product.price);

		Dom.value('#sale_price', product.sale_price);

		Dom.value('#description', product.description);

		Dom.value('#category_id', product.category_id);

		Dom.value('#brand_id', product.brand_id);

		Dom.value('#status', product.status);

		this.renderThumbnail(product.thumbnail);
	},

	/* =================================================
		THUMBNAIL
	================================================= */

	renderThumbnail(thumbnail) {
		const preview = Dom.find('#thumbnail-preview');

		if (!preview) {
			return;
		}

		if (!thumbnail) {
			preview.src = '';

			preview.classList.add('d-none');

			return;
		}

		preview.src = thumbnail instanceof File
			? URL.createObjectURL(thumbnail)
			: `/uploads/products/${thumbnail}`;

		preview.classList.remove('d-none');
	}
};

export default Renderer;
