const Autocomplete = {
	/* =================================================
	   PUBLIC
	================================================= */

	init(options) {
		const element = document.querySelector(options.element);

		if (!element) {
			return;
		}

		const dropdown = this.createDropdown(element);

		let timer;

		element.addEventListener('input', () => {
			clearTimeout(timer);

			const keyword = element.value.trim();

			if (!keyword) {
				this.close(dropdown);

				return;
			}

			timer = setTimeout(async () => {
				const items = await options.source(keyword);

				this.render(dropdown, items, options.select, options.field);
			}, options.delay ?? 300);
		});

		document.addEventListener('click', (event) => {
			if (!element.contains(event.target) && !dropdown.contains(event.target)) {
				this.close(dropdown);
			}
		});
	},

	/* =================================================
	   RENDER
	================================================= */

	render(dropdown, items, select, field = 'name') {
		dropdown.innerHTML = '';

		if (!items || !items.length) {
			this.close(dropdown);

			return;
		}

		items.forEach((item) => {
			const option = this.createItem(item, select, dropdown, field);

			dropdown.appendChild(option);
		});

		this.open(dropdown);
	},

	createItem(item, select, dropdown, field) {
		const button = document.createElement('button');

		button.type = 'button';

		button.className = 'list-group-item list-group-item-action';

		button.textContent = item[field] ?? '';

		button.addEventListener('click', () => {
			select(item);

			this.close(dropdown);
		});

		return button;
	},

	/* =================================================
	   DROPDOWN
	================================================= */

	createDropdown(element) {
		const dropdown = document.createElement('div');

		dropdown.className = 'list-group position-absolute w-100';

		dropdown.style.maxHeight = '220px';

		dropdown.style.overflowY = 'auto';

		dropdown.style.zIndex = '1050';

		element.parentNode.appendChild(dropdown);

		this.close(dropdown);

		return dropdown;
	},

	open(dropdown) {
		dropdown.style.display = 'block';
	},

	close(dropdown) {
		dropdown.style.display = 'none';
	},
};

export default Autocomplete;
