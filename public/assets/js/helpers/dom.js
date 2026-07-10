const Dom = {
	query(selector) {
		return document.querySelector(selector);
	},

	setValue(selector, value) {
		const element = this.query(selector);

		if (element) {
			element.value = value ?? '';
		}
	},

	setText(selector, value) {
		const element = this.query(selector);

		if (element) {
			element.textContent = value ?? '';
		}
	},

	html(selector, html) {
		const element = this.query(selector);

		if (element) {
			element.innerHTML = html;
		}
	},

	show(element) {
		if (element) {
			element.classList.remove('d-none');
		}
	},

	hide(element) {
		if (element) {
			element.classList.add('d-none');
		}
	},

	toggle(element, condition) {
		if (element) {
			element.classList.toggle('d-none', condition);
		}
	},
};

export default Dom;
