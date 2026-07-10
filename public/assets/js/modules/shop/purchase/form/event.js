import Controller from './controller.js';
import Autocomplete from '../../../components/autocomplete.js';

const Event = {
	bind() {
		this.autocomplete();

		this.purchase();

		this.items();

		this.submit();
	},

	/* ===============================
	   AUTOCOMPLETE
	=============================== */

	autocomplete() {
		Autocomplete.init({
			input: '#supplier_search',

			dropdown: '#supplier_suggestions',

			itemClass: 'supplier-item',

			onSearch: Controller.searchSupplier,

			onSelect: Controller.selectSupplier,
		});

		Autocomplete.init({
			input: '#product_search',

			dropdown: '#product_suggestions',

			itemClass: 'product-item',

			onSearch: Controller.searchProduct,

			onSelect: Controller.selectProduct,
		});
	},

	/* ===============================
	   PURCHASE
	=============================== */

	purchase() {
		const events = {
			'#description': ['input', Controller.changeDescription],

			'#status': ['change', Controller.changeStatus],

			'#warehouse_id': ['change', Controller.changeWarehouse],

			'#payment': ['change', Controller.changePayment],

			'#paid_amount': ['input', Controller.changePaidAmount],

			'#vat_rate': ['input', Controller.changeVatRate],
		};

		Object.entries(events).forEach(([selector, [event, handler]]) => {
			document.querySelector(selector)?.addEventListener(event, handler);
		});
	},

	/* ===============================
	   ITEMS
	=============================== */

	items() {
		const table = document.querySelector('#selected_products');

		if (!table) return;

		table.addEventListener('input', Controller.changeItem);

		table.addEventListener('click', Controller.removeItem);
	},

	/* ===============================
	   SUBMIT
	=============================== */

	submit() {
		document.querySelector('#purchase-form')?.addEventListener('submit', Controller.submit);
	},
};

export default Event;
