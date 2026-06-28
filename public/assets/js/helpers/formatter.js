export const Formatter = {

    /**
     * FORMAT MONEY (VND)
     */
    money(value) {

        if (value === null || value === undefined || isNaN(value)) {
            return '0 ₫';
        }

        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(value);
    },

    /**
     * FORMAT NUMBER (không tiền tệ)
     */
    number(value) {

        if (value === null || value === undefined || isNaN(value)) {
            return '0';
        }

        return new Intl.NumberFormat('vi-VN').format(value);
    },

    /**
     * FORMAT DATE (YYYY-MM-DD -> DD/MM/YYYY)
     */
    date(value) {

        if (!value) return '';

        const date = new Date(value);

        if (isNaN(date.getTime())) {
            return '';
        }

        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();

        return `${day}/${month}/${year}`;
    },

    /**
     * FORMAT DATETIME
     */
    datetime(value) {

        if (!value) return '';

        const date = new Date(value);

        if (isNaN(date.getTime())) {
            return '';
        }

        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();

        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');

        return `${day}/${month}/${year} ${hours}:${minutes}`;
    }

};