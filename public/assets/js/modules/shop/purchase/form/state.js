/**
 * =========================================================
 * PURCHASE FORM STATE
 * Create / Edit / View Purchase
 * =========================================================
 */

export const state = {

    supplier: {
        selected: { id: null, name: "" },
        search: {
            keyword: "",
            results: [],
            open: false,
            loading: false
        }
    },

    warehouse: {
        selected: { id: null, name: "" },
        list: []
    },

    products: {
        items: [],
        search: {
            keyword: "",
            results: [],
            open: false,
            loading: false
        }
    },

    payment: {
        status: "unpaid",
        paid_amount: 0
    },

    summary: {
        total_amount: 0,
        debt_amount: 0
    },

    meta: {
        id: null,
        code: null,
        status: "draft",
        description: "",
        created_at: null,
        updated_at: null
    },

    ui: {
        loading: false
    }

};