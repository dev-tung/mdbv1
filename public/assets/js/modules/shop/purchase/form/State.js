const State = {

    supplier: {
        id: null,
        keyword: '',
        suggestions: []
    },

    warehouse: {
        id: null,
        list: []
    },

    purchase: {
        id: null,
        description: '',
        status: '',
        payment: '',
        paid_amount: 0
    },

    product: {
        keyword: '',
        suggestions: [],
        selected: []
        /*
        [
            {
                id: 1,
                name: '',
                quantity: 1,
                purchase_price: 0,
                total_amount: 0
            }
        ]
        */
    },

    summary: {
        total_amount: 0,
        paid_amount: 0,
        debt_amount: 0
    },

    reset() {

        this.supplier = {
            id: null,
            keyword: '',
            suggestions: []
        };

        this.warehouse = {
            id: null,
            list: []
        };

        this.purchase = {
            id: null,
            description: '',
            status: '',
            payment: '',
            paid_amount: 0
        };

        this.product = {
            keyword: '',
            suggestions: [],
            selected: []
        };

        this.summary = {
            total_amount: 0,
            paid_amount: 0,
            debt_amount: 0
        };

    }

};

export default State;