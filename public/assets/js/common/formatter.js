// public/assets/js/helpers/formatter.js

export const Formatter = {

    money(value = 0) {

        return Number(value).toLocaleString(
            'vi-VN',
            {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }
        );
    },

    number(value = 0) {
        return Number(value).toLocaleString('vi-VN');
    },

    date(value) {

        if (!value) return '';

        return new Date(value)
            .toLocaleDateString('vi-VN');
    },

    datetime(value) {

        if (!value) return '';

        return new Date(value)
            .toLocaleString('vi-VN');
    }

};