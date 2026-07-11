const Calculator = {
	sum(items = [], field) {
		return items.reduce((total, item) => total + Number(item[field] || 0), 0);
	},

	add(...numbers) {
		return numbers.reduce((total, number) => total + Number(number || 0), 0);
	},

	subtract(a = 0, b = 0) {
		return Number(a || 0) - Number(b || 0);
	},

	multiply(a = 0, b = 0) {
		return Number(a || 0) * Number(b || 0);
	},

	percent(value = 0, rate = 0) {
		return (Number(value || 0) * Number(rate || 0)) / 100;
	},
};

export default Calculator;
