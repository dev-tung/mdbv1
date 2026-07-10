const Autocomplete = {
	init(options) {
		this.input = document.querySelector(options.input);

		this.box = document.querySelector(options.dropdown);

		this.className = options.itemClass ?? 'autocomplete-item';

		this.onSelect = options.onSelect;

		this.render(options.data ?? []);
	},

	render(data) {
		this.box.innerHTML = '';

		if (!data.length) {
			this.box.classList.add('d-none');

			return;
		}

		data.forEach((item) => {
			this.box.insertAdjacentHTML(
				'beforeend',
				`
				<button
					type="button"
					class="
						list-group-item
						list-group-item-action
						${this.className}
					"
					data-id="${item.id}">

					${item.name}

				</button>
				`,
			);
		});

		this.box.classList.remove('d-none');

		this.events();
	},

	events() {
		this.box.querySelectorAll('.' + this.className).forEach((button) => {
			button.addEventListener('click', () => {
				const id = button.dataset.id;

				this.onSelect({
					id: id,
					name: button.textContent.trim(),
				});

				this.close();
			});
		});
	},

	close() {
		this.box.classList.add('d-none');
	},
};

export default Autocomplete;
