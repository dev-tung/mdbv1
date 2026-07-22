import State from './state.js';

import Dom from '../../../../helpers/dom.js';
import Formatter from '../../../../helpers/formatter.js';

import Select from '../../../../components/select.js';
import Pagination from '../../../../components/pagination.js';

const Renderer = {
	/* =================================================
	   PUBLIC
	================================================= */

	render() {
		this.renderFilters();

		this.renderProducts();

		this.renderPagination();
	},

	/* =================================================
	   FILTER
	================================================= */

	renderFilters() {
		Select.render(
			'#filter-category',
			State.categories,
			State.filters.category_id,
			'Tất cả danh mục',
		);

		Select.render(
			'#filter-brand',
			State.brands,
			State.filters.brand_id,
			'Tất cả thương hiệu',
		);
	},

	/* =================================================
	   PRODUCTS
	================================================= */

	renderProducts() {
		const uploadPath = '/uploads/products/';
		const noImage = '/assets/image/no-image.svg';

		const container = Dom.find('#product-grid');

		if (!container) {
			return;
		}

		container.replaceChildren();

		if (!State.products.length) {
			const col = document.createElement('div');

			col.className = 'col-12';

			const alert = document.createElement('div');

			alert.className = 'alert alert-light border text-center mb-0';

			alert.textContent = 'Không có sản phẩm.';

			col.appendChild(alert);

			container.appendChild(col);

			return;
		}

		const fragment = document.createDocumentFragment();

		State.products.forEach((product) => {
			const template = Dom.template('#product-card-template');

			const card = template.querySelector('.product-card');

			// =========================
			// IMAGE
			// =========================

			const image = card.querySelector('.product-image');

			if (image) {
				image.src = product.thumbnail
					? product.thumbnail.startsWith('uploads/')
						? `/${product.thumbnail}`
						: `${uploadPath}${product.thumbnail}`
					: noImage;

				image.alt = product.name;
			}

			// =========================
			// TEXT
			// =========================

			const texts = {
				'.product-brand': product.brand_name || '',

				'.product-name': product.name,
			};

			Object.entries(texts).forEach(([selector, value]) => {
				Dom.text(selector, value, card);
			});

			// =========================
			// PRICE
			// =========================

			const priceElement = card.querySelector('.product-price');

			const price = Number(product.price);

			const salePrice = Number(product.sale_price);

			if (salePrice > 0 && salePrice < price) {
				priceElement.innerHTML = `
					<span class="text-decoration-line-through text-secondary me-2">
						${Formatter.money(price)}
					</span>

					<span class="text-danger fw-bold">
						${Formatter.money(salePrice)}
					</span>
				`;
			} else {
				priceElement.textContent = Formatter.money(price);
			}

			// =========================
			// LINK
			// =========================

			const link = card.querySelector('.product-link');

			if (link) {
				link.href = `/product/${product.slug || product.id}`;
			}

			fragment.appendChild(template);
		});

		container.appendChild(fragment);
	},

	/* =================================================
	   PAGINATION
	================================================= */

	renderPagination() {
		Pagination.render(
			'#pagination',
			State.pagination,
			State.onPageChange,
		);
	},
};

export default Renderer;