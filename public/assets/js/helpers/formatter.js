const Formatter = {
	money(value) {
		return Number(value ?? 0).toLocaleString('vi-VN');
	},

	date(value) {
		if (!value) return '';

		return new Date(value).toLocaleDateString('vi-VN');
	},
};

export default Formatter;
