import Dom from '../helpers/dom.js';

const Table = {
	config: {
		body: '',
		colspan: 1,

		filters: {},

		source: null,
		render: null,
	},

	async init(config = {}) {
		this.config = {
			...this.config,
			...config,
		};

		this.bindFilters();

		await this.load();
	},

	bindFilters() {
		Object.entries(this.config.filters).forEach(([selector, option]) => {
			const element = Dom.find(selector);

			if (!element) {
				return;
			}

			const event = option.event || 'change';

			element.addEventListener(event, async (e) => {
				if (typeof option.handler === 'function') {
					option.handler(e.target.value, e);
				}

				await this.load();
			});
		});
	},

	async load() {
		if (typeof this.config.source === 'function') {
			await this.config.source();
		}

		if (typeof this.config.render === 'function') {
			this.config.render();
		}
	},

	renderBody(data, renderRow, emptyMessage = 'Không có dữ liệu') {
		const tbody = Dom.find(this.config.body);

		if (!tbody) {
			return;
		}

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
};

export default Table;