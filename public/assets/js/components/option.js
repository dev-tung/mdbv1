const Option = {
	render(options = {}) {
		const { data = [], selected = '', value = 'id', label = 'name' } = options;

		if (Array.isArray(data)) {
			return data
				.map(
					(item) => `
						<option
							value="${item[value]}"
							${String(item[value]) === String(selected) ? 'selected' : ''}
						>
							${item[label]}
						</option>
					`,
				)
				.join('');
		}

		return Object.entries(data)
			.map(
				([key, item]) => `
					<option
						value="${key}"
						${String(key) === String(selected) ? 'selected' : ''}
					>
						${item[label]}
					</option>
				`,
			)
			.join('');
	},
};

export default Option;
