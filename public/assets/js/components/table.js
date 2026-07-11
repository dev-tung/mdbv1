const Table = {
	init(options) {
		this.element = options.element;

		if (!this.element) return;

		this.data = options.data ?? [];

		this.columns = options.columns ?? 100;

		this.empty = options.empty ?? 'Không có dữ liệu';

		this.cells = options.cells;

		this.attributes = options.attributes;

		this.render();
	},

	render() {
		if (!this.data.length) {
			this.element.innerHTML = `
				<tr>
					<td colspan="${this.columns}" class="text-center text-muted py-4">
						${this.empty}
					</td>
				</tr>
			`;

			return;
		}

		this.element.innerHTML = this.data
			.map(
				(item, index) => `
				<tr ${this.attributes?.(item, index) ?? ''}>
					${this.renderCells(item, index)}
				</tr>
			`,
			)
			.join('');
	},

	renderCells(item, index) {
		return this.cells(item, index)
			.map((cell) => {
				if (typeof cell !== 'object') {
					cell = {
						content: cell,
					};
				}

				return `
					<td
						class="${cell.class ?? ''}"
						${cell.width ? `width="${cell.width}"` : ''}
					>
						${cell.content ?? ''}
					</td>
				`;
			})
			.join('');
	},
};

export default Table;
