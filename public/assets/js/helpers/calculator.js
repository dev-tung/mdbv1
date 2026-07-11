const Calculator = {

    add(...numbers) {
        return numbers.reduce((sum, number) => {
            return sum + Number(number || 0);
        }, 0);
    },

    subtract(a, b) {
        return Number(a || 0) - Number(b || 0);
    },

    multiply(a, b) {
        return Number(a || 0) * Number(b || 0);
    },

    divide(a, b) {
        b = Number(b || 0);

        return b === 0 ? 0 : Number(a || 0) / b;
    },

    percent(value, rate) {
        return this.multiply(value, rate) / 100;
    },

    sum(items, callback) {

        return items.reduce((total, item) => {
            return total + Number(callback(item) || 0);
        }, 0);

    },

    round(value, precision = 2) {

        const factor = 10 ** precision;

        return Math.round(Number(value || 0) * factor) / factor;

    }

};

export default Calculator;