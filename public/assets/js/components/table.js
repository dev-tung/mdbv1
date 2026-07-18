import Dom from '../helpers/dom.js';

const Table = {
	config: {
		body: '',
		colspan: 1,

		pagination: false,

		filters: {},

		page: 1,
		per_page: 20,

		source: null,
		render: null,
	},

	state: {
		total: 0,
		last_page: 1,
	},

	async init(config = {}) {
		this.config = {
			...this.config,
			...config,
		};

		this.createPagination();

		this.bindFilters();

		this.bindPagination();

		await this.load();
	},

	createPagination() {
		if (!this.config.pagination) {
			return;
		}

		const tbody = Dom.find(this.config.body);

		if (!tbody) {
			return;
		}

		const table = tbody.closest('table');

		if (!table) {
			return;
		}

		const wrapper = document.createElement('div');

		wrapper.className = 'd-flex gap-1 mt-3';

		wrapper.id = `${tbody.id}-pagination`;

		table.parentElement.appendChild(wrapper);

		this.config.pagination = `#${wrapper.id}`;
	},

	bindFilters() {
		Object.entries(this.config.filters).forEach(([selector, option]) => {
			const element = Dom.find(selector);

			if (!element) {
				return;
			}

			const event = option.event || 'change';

			element.addEventListener(event, async (e) => {
				this.config.page = 1;

				if (typeof option.handler === 'function') {
					option.handler(e.target.value, e);
				}

				await this.load();
			});
		});
	},

	bindPagination() {
		if (!this.config.pagination) {
			return;
		}

		const container = Dom.find(this.config.pagination);

		if (!container) {
			return;
		}

		container.addEventListener('click', async (e) => {
			const button = e.target.closest('[data-page]');

			if (!button) {
				return;
			}

			const page = Number(button.dataset.page);

			if (page === this.config.page) {
				return;
			}

			this.config.page = page;

			await this.load();
		});
	},

	async load() {
		let response = null;

		if (typeof this.config.source === 'function') {
			response = await this.config.source({
				page: this.config.page,

				per_page: this.config.per_page,
			});
		}

		if (response?.pagination) {
			this.state.total = response.pagination.total;

			this.state.last_page = response.pagination.last_page;

			this.renderPagination();
		}

		if (typeof this.config.render === 'function') {
			this.config.render(response);
		}
	},

	renderPagination() {
		if (!this.config.pagination) {
			return;
		}

		const container = Dom.find(this.config.pagination);

		if (!container) {
			return;
		}

		container.replaceChildren();

		const current = this.config.page;

		const last = this.state.last_page;

		if (last <= 1) {
			return;
		}

		const createButton = (page, text = page, active = false) => {
			const button = document.createElement('button');

			button.type = 'button';

			button.dataset.page = page;

			button.className = 'btn btn-sm ' + (active ? 'btn-secondary' : 'btn-outline-secondary');

			button.textContent = text;

			return button;
		};

		const pages = [];

		if (last <= 5) {
			for (let i = 1; i <= last; i++) {
				pages.push(i);
			}
		} else {
			pages.push(1);

			if (current > 3) {
				pages.push('...');
			}

			const start = Math.max(2, current - 1);

			const end = Math.min(last - 1, current + 1);

			for (let i = start; i <= end; i++) {
				pages.push(i);
			}

			if (current < last - 2) {
				pages.push('...');
			}

			pages.push(last);
		}

		pages.forEach((page) => {
			if (page === '...') {
				const span = document.createElement('span');

				span.className = 'btn btn-sm btn-light disabled';

				span.textContent = '...';

				container.appendChild(span);

				return;
			}

			container.appendChild(createButton(page, page, page === current));
		});
	},

	renderBody(data, renderRow, emptyMessage = 'Không có dữ liệu') {
		const tbody = Dom.find(this.config.body);

		if (!tbody) {
			return;
		}

		tbody.replaceChildren();

		if (!data || !data.length) {
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
};

export default Table;
