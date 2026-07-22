const Formatter = {
	/* =================================================
       NUMBER
    ================================================= */

	number(value, locales = 'vi-VN') {
		return Number(value || 0).toLocaleString(locales);
	},

	money(value, locales = 'vi-VN', suffix = ' đ') {
		return `${Number(value || 0).toLocaleString(locales)}${suffix}`;
	},

	percent(value, digits = 0) {
		return `${Number(value || 0).toFixed(digits)}%`;
	},

	/* =================================================
       DATE
    ================================================= */

	date(value, locales = 'vi-VN') {
		if (!value) return '';

		return new Date(value).toLocaleDateString(locales);
	},

	datetime(value, locales = 'vi-VN') {
		if (!value) return '';

		return new Date(value).toLocaleString(locales);
	},

	time(value, locales = 'vi-VN') {
		if (!value) return '';

		return new Date(value).toLocaleTimeString(locales);
	},

	/* =================================================
       STRING
    ================================================= */

	upper(value) {
		return String(value ?? '').toUpperCase();
	},

	lower(value) {
		return String(value ?? '').toLowerCase();
	},

	capitalize(value) {
		value = String(value ?? '');

		return value.charAt(0).toUpperCase() + value.slice(1);
	},
};

export default Formatter;
