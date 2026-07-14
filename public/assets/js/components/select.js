import Dom from '../helpers/dom.js';

const Select = {
	render(target, options, selected = null, placeholder = null) {
		const select =
			typeof target === 'string'
				? Dom.find(target)
				: target;

		if (!select) {
			return;
		}

		select.innerHTML = '';

		// Xóa class màu cũ
		select.className = select.className.replace(/\btext-\S+\b/g, '');

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

		// Thêm màu theo option đang chọn
		if (options[selected]?.color) {
			select.classList.add(`text-${options[selected].color}`);
		}
	},
};

export default Select;