import Dom from '../helpers/dom.js';

const Table = {
	config: {
		body: '',
		pagination: '',
		colspan: 1,

		render: null,
		onPage: null,
	},

	init(config = {}) {
		this.config = {
			...this.config,
			...config,
		};
	},

	refresh() {
		if (typeof this.config.render === 'function') {
			this.config.render();
		}
	},

	renderBody(data, renderRow, emptyMessage = 'Không có dữ liệu') {
		const tbody = Dom.find(this.config.body);

		tbody.replaceChildren();

		if (!data.length) {
			const tr = document.createElement('tr');

			const td = document.createElement('td');

			td.colSpan = this.config.colspan;
			td.className = 'text-center text-muted';
			td.textContent = emptyMessage;

			tr.appendChild(td);

			tbody.appendChild(tr);

			return;
		}

		data.forEach((item, index) => {
			tbody.appendChild(renderRow(item, index));
		});
	},

	renderPagination({ page = 1, total_pages = 1 }) {
		const container = Dom.find(this.config.pagination);

		container.replaceChildren();

		if (total_pages <= 1) {
			return;
		}

		const nav = document.createElement('nav');

		const ul = document.createElement('ul');

		ul.className = 'pagination pagination-sm mb-0';

		// Previous
		ul.appendChild(
			this.createItem({
				label: '«',
				page: page - 1,
				disabled: page === 1,
			}),
		);

		// Pages
		for (let i = 1; i <= total_pages; i++) {
			ul.appendChild(
				this.createItem({
					label: i,
					page: i,
					active: i === page,
				}),
			);
		}

		// Next
		ul.appendChild(
			this.createItem({
				label: '»',
				page: page + 1,
				disabled: page === total_pages,
			}),
		);

		nav.appendChild(ul);

		container.appendChild(nav);
	},

	createItem({
		label,
		page,
		active = false,
		disabled = false,
	}) {
		const li = document.createElement('li');

		li.className = 'page-item';

		if (active) {
			li.classList.add('active');
		}

		if (disabled) {
			li.classList.add('disabled');
		}

		const button = document.createElement('button');

		button.type = 'button';
		button.className = 'page-link';
		button.textContent = label;

		if (!disabled) {
			button.addEventListener('click', () => {
				if (typeof this.config.onPage === 'function') {
					this.config.onPage(page);
				}
			});
		}

		li.appendChild(button);

		return li;
	},
};

export default Table;