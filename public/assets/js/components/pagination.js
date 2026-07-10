const Pagination = {
	init(options) {
		this.element = options.element;

		if (!this.element) return;

		this.current = options.current ?? 1;

		this.total = options.total ?? 1;

		this.onChange = options.onChange;

		this.render();

		this.events();
	},

	render() {
		if (this.total <= 1) {
			this.element.innerHTML = '';

			return;
		}

		let html = '';

		html += `
			<li class="page-item ${this.current === 1 ? 'disabled' : ''}">
				<button
					type="button"
					class="page-link"
					data-page="${this.current - 1}"
				>
					&laquo;
				</button>
			</li>
		`;

		for (let page = 1; page <= this.total; page++) {
			html += `
				<li class="page-item ${page === this.current ? 'active' : ''}">
					<button
						type="button"
						class="page-link"
						data-page="${page}"
					>
						${page}
					</button>
				</li>
			`;
		}

		html += `
			<li class="page-item ${this.current === this.total ? 'disabled' : ''}">
				<button
					type="button"
					class="page-link"
					data-page="${this.current + 1}"
				>
					&raquo;
				</button>
			</li>
		`;

		this.element.innerHTML = html;
	},

	events() {
		this.element.querySelectorAll('[data-page]').forEach((button) => {
			button.addEventListener('click', () => {
				const page = Number(button.dataset.page);

				if (page < 1 || page > this.total || page === this.current) {
					return;
				}

				this.onChange?.(page);
			});
		});
	},
};

export default Pagination;
