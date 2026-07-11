const Pricing = {

    /* =================================================
       ITEM
    ================================================= */

    subtotal(quantity, price) {
        return Number(quantity || 0) * Number(price || 0);
    },

    tax(subtotal, taxRate = 0) {
        return Number(subtotal || 0) * Number(taxRate || 0) / 100;
    },

    total(subtotal, tax = 0) {
        return Number(subtotal || 0) + Number(tax || 0);
    },

    /* =================================================
       SUMMARY
    ================================================= */

    summary(items = [], taxRate = 0, priceField = 'price') {

        return items.reduce((summary, item) => {

            const subtotal = this.subtotal(
                item.quantity,
                item[priceField]
            );

            const tax = this.tax(subtotal, taxRate);

            const total = this.total(subtotal, tax);

            summary.subtotal += subtotal;
            summary.tax += tax;
            summary.total += total;

            return summary;

        }, {
            subtotal: 0,
            tax: 0,
            total: 0,
        });

    },

    /* =================================================
       PAYMENT
    ================================================= */

    debt(total, paid = 0) {
        return Number(total || 0) - Number(paid || 0);
    }

};

export default Pricing;