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

        status: '',

        payment: '',

        paid_amount: 0,

        total_amount: 0,

        debt_amount: 0,

        total_amount_with_vat: 0,

        items: [
            /*
            {
                product_id: 1,
                name: 'Yonex 88D Pro',
                quantity: 2,
                purchase_price: 1200000,
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

        this.warehouse = {
            list: []
        };

        this.purchase = {

            id: null,

            supplier_id: null,

            warehouse_id: null,

            description: '',

            status: '',

            payment: '',

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