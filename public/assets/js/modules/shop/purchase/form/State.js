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
       WAREHOUSE
    ================================================= */

    warehouse: {
        list: []
    },

    /* =================================================
       PURCHASE
    ================================================= */

    purchase: {

        id: null,

        supplier_id: null,

        supplier_name: '',

        warehouse_id: null,

        description: '',

        status: 'draft',

        payment: 'unpaid',

        paid_amount: 0,

        subtotal_amount: 0,

        vat_rate: 0,

        vat_amount: 0,

        total_amount: 0,

        debt_amount: 0,

        items: [
            /*
            {
                product_id: 1,
                product_name: 'Yonex 88D Pro',

                quantity: 2,

                purchase_price: 1200000,
                selling_price: 1500000,

                subtotal_amount: 2400000
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

        this.warehouse = {
            list: []
        };

        this.purchase = {

            id: null,

            supplier_id: null,

            supplier_name: '',

            warehouse_id: null,

            description: '',

            status: 'draft',

            payment: 'unpaid',

            paid_amount: 0,

            subtotal_amount: 0,

            vat_rate: 0,

            vat_amount: 0,

            total_amount: 0,

            debt_amount: 0,

            items: []

        };

    }

};

State.reset();

export default State;