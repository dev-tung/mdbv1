import Dom from '../helpers/dom.js';

const Select = {
	render(selector, options, selected = null, placeholder = null) {
		const select = Dom.find(selector);

		select.innerHTML = '';

		if (placeholder) {
			const option = document.createElement('option');

			option.value = '';
			option.textContent = placeholder;
			option.selected = selected == null || selected === '';

			select.append(option);
		}

		Object.entries(options).forEach(([value, option]) => {
			const element = document.createElement('option');

			element.value = value;
			element.textContent = option.label;
			element.selected = String(value) === String(selected);

			select.append(element);
		});
	},
};

export default Select;