const Calculator = {

    amount(quantity, price) {

        return (Number(quantity) || 0) * (Number(price) || 0);

    },

    vat(amount, vatRate) {

        return Math.round(
            (Number(amount) || 0) * (Number(vatRate) || 0) / 100
        );

    },

    total(amount, vat) {

        return (Number(amount) || 0) + (Number(vat) || 0);

    },

    debt(total, paid) {

        return Math.max(
            (Number(total) || 0) - (Number(paid) || 0),
            0
        );

    },

    sum(values) {

        return values.reduce(
            (sum, value) => sum + (Number(value) || 0),
            0
        );

    },

    discount(amount, discount) {

        return (Number(amount) || 0) - (Number(discount) || 0);

    },

    percent(amount, rate) {

        return (Number(amount) || 0) * (Number(rate) || 0) / 100;

    }

};

export default Calculator;