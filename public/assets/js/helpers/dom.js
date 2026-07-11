const Dom = {
	query(selector, parent = document) {
		return parent.querySelector(selector);
	},

	queryAll(selector, parent = document) {
		return [...parent.querySelectorAll(selector)];
	},

	setValue(selector, value, parent = document) {
		const element = this.query(selector, parent);

		if (element) {
			element.value = value ?? '';
		}
	},

	setText(selector, value, parent = document) {
		const element = this.query(selector, parent);

		if (element) {
			element.textContent = value ?? '';
		}
	},

	html(selector, html, parent = document) {
		const element = this.query(selector, parent);

		if (element) {
			element.innerHTML = html;
		}
	},

	append(selector, html, parent = document) {
		const element = this.query(selector, parent);

		if (element) {
			element.insertAdjacentHTML('beforeend', html);
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
