import Dom from '../../../../helpers/dom.js';

import State from './state.js';
import Renderer from './renderer.js';
import Service from './service.js';

const Init = {

	async init() {

		// =========================
		// GET FILTER FROM URL
		// =========================

		const params =
			new URLSearchParams(
				window.location.search
			);

		State.filters.keyword =
			params.get('keyword') || '';

		State.filters.category_id =
			params.get('category') || '';


		// =========================
		// SEARCH INPUT
		// =========================

		const searchInput =
			Dom.find(
				'input[name="keyword"]'
			);

		if (searchInput) {

			searchInput.value =
				State.filters.keyword;
		}


		// =========================
		// CATEGORY
		// =========================

		const categorySelect =
			Dom.find('#filter-category');

		if (categorySelect) {

			categorySelect.value =
				State.filters.category_id;
		}


		// =========================
		// EVENTS
		// =========================

		this.bindEvents();


		// =========================
		// LOAD PRODUCTS
		// =========================

		await this.loadProducts();
	},


	/* =================================================
		PRODUCTS
	================================================= */

	async loadProducts(page = 1) {

		try {

			// =========================
			// SEARCH
			// =========================

			const searchInput =
				Dom.find(
					'input[name="keyword"]'
				);

			if (searchInput) {

				State.filters.keyword =
					searchInput.value.trim();
			}


			// =========================
			// API
			// =========================

			const data =
				await Service.getList({

					...State.filters,

					website: 1,

					page,

					per_page:
						State.pagination.per_page,

				});


			// =========================
			// STATE
			// =========================

			State.setDefault(data);


			// =========================
			// PAGINATION
			// =========================

			State.onPageChange =
				(page) => {

					this.loadProducts(page);

				};


			// =========================
			// RENDER
			// =========================

			Renderer.render();

		} catch (error) {

			alert(error.message);
		}
	},


	/* =================================================
		EVENTS
	================================================= */

	bindEvents() {


		// =========================
		// CATEGORY
		// =========================

		Dom.find(
			'#filter-category'
		)?.addEventListener(
			'change',
			async (e) => {

				State.filters.category_id =
					e.target.value;

				await this.loadProducts();

			}
		);


		// =========================
		// BRAND
		// =========================

		Dom.find(
			'#filter-brand'
		)?.addEventListener(
			'change',
			async (e) => {

				State.filters.brand_id =
					e.target.value;

				await this.loadProducts();

			}
		);


		// =========================
		// PRICE
		// =========================

		Dom.find(
			'#filter-price'
		)?.addEventListener(
			'change',
			async (e) => {

				State.filters.price =
					e.target.value;

				await this.loadProducts();

			}
		);


		// =========================
		// STATUS
		// =========================

		Dom.find(
			'#filter-status'
		)?.addEventListener(
			'change',
			async (e) => {

				State.filters.status =
					e.target.value;

				await this.loadProducts();

			}
		);


		// =========================
		// BUY NOW
		// =========================

		const productGrid = Dom.find('#product-grid');

		if (productGrid) {

			productGrid.addEventListener('click', (e) => {

				const button = e.target.closest('.product-buy-now');

				if (!button) {
					return;
				}

				const productId = Number(
					button.dataset.productId
				);

				if (!productId) {
					console.error('Không tìm thấy product_id.');
					return;
				}

				const product = State.products.find(
					(item) => Number(item.id) === productId
				);

				if (!product) {
					console.error(
						'Không tìm thấy sản phẩm trong State.'
					);
					return;
				}


				// =========================
				// PURCHASE ID
				// =========================

				const purchaseId =
					Number(product.purchase_id || 0);


				if (!purchaseId) {

					console.error(
						'Không tìm thấy purchase_id của sản phẩm.'
					);

					window.location.href =
						`/product/${product.slug || product.id}`;

					return;
				}


				// =========================
				// PRICE
				// =========================

				const price =
					Number(product.price || 0);

				const salePrice =
					Number(product.sale_price || 0);

				const sellingPrice =
					salePrice > 0 &&
					price > 0 &&
					salePrice < price
						? salePrice
						: price;


				// =========================
				// STOCK
				// =========================

				const stock =
					Number(product.stock || 0);


				if (
					sellingPrice <= 0 ||
					stock <= 0
				) {

					window.location.href =
						`/product/${product.slug || product.id}`;

					return;
				}


				// =========================
				// GET CART
				// =========================

				let cart = [];

				try {

					cart =
						JSON.parse(
							localStorage.getItem('cart')
						) || [];

					if (!Array.isArray(cart)) {
						cart = [];
					}

				} catch (error) {

					cart = [];
				}


				// =========================
				// FIND EXISTING ITEM
				// product + purchase
				// =========================

				const index =
					cart.findIndex(
						(item) =>
							Number(item.product_id) === productId &&
							Number(item.purchase_id) === purchaseId
					);


				// =========================
				// EXISTING
				// =========================

				if (index >= 0) {

					let quantity =
						Number(
							cart[index].quantity || 0
						);

					quantity++;

					if (quantity > stock) {
						quantity = stock;
					}

					cart[index].quantity =
						quantity;

					cart[index].product_id =
						productId;

					cart[index].purchase_id =
						purchaseId;

					cart[index].name =
						product.name;

					cart[index].price =
						sellingPrice;

					cart[index].image =
						product.thumbnail || '';

					cart[index].stock =
						stock;
				}


				// =========================
				// NEW
				// =========================

				else {

					cart.push({

						product_id:
							productId,

						purchase_id:
							purchaseId,

						name:
							product.name,

						price:
							sellingPrice,

						image:
							product.thumbnail || '',

						stock:
							stock,

						quantity:
							1
					});
				}


				// =========================
				// SAVE CART
				// =========================

				localStorage.setItem(
					'cart',
					JSON.stringify(cart)
				);


				// =========================
				// GO CART
				// =========================

				window.location.href =
					'/cart';
			});
		}
	},
};


export default Init;


document.addEventListener(
	'DOMContentLoaded',
	() => {

		Init.init();

	}
);