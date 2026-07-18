import Dom from '../helpers/dom.js';

const Label = {
	render(target, options = {}, value = null) {
		const element = typeof target === 'string'
			? Dom.find(target)
			: target;

		if (!element) {
			return;
		}

		element.textContent = options[value]?.label ?? '';
	},
};

export default Label;