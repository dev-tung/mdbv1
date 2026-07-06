const State = {

    /* =================================================
       SUPPLIER SEARCH
    ================================================= */

    supplier: {
        keyword: '',
        suggestions: []
    },

    /* =================================================
       PRODUCT SEARCH
    ================================================= */

    product: {
        keyword: '',
        suggestions: []
    },

    /* =================================================
       ORDER
    ================================================= */

    order: {

        id: null,

        supplier_id: null,

        supplier_name: '',

        description: '',

        status: 'draft',

        payment: '',

        paid_amount: 0,

        total_amount: 0,

        debt_amount: 0,

        total_amount_with_vat: 0,

        items: [
            /*
            {
                product_id: 1,
                product_name: 'Yonex 88D Pro',
                quantity: 2,
                order_price: 1200000,
                order_price: 1200000,
                total_amount: 2400000
            }
            */
        ]

    },

    /* =================================================
       RESET
    ================================================= */

    reset() {

        this.supplier = {
            keyword: '',
            suggestions: []
        };

        this.product = {
            keyword: '',
            suggestions: []
        };

        this.order = {

            id: null,

            supplier_id: null,

            description: '',

            status: 'draft',

            payment: 'unpaid',

            paid_amount: 0,

            total_amount: 0,

            total_amount_with_vat: 0,

            debt_amount: 0,

            items: []

        };

    }

};

State.reset();

export default State;