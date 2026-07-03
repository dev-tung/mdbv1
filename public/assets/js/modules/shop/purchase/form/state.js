/**
 * =========================================================
 * PURCHASE FORM
 * Dùng cho:
 * - Create Purchase
 * - Edit Purchase
 * - View Purchase
 * =========================================================
 */
export const state = {

    // SUPPLIER
    supplier: {
        selected: { id: null, name: "" },
        search: { keyword: "", results: [], open: false, loading: false }
    },

    // WAREHOUSE
    warehouse: {
        selected: { id: null, name: "" },
        list: []
    },

    // PRODUCTS
    products: {
        items: [],
        search: { keyword: "", results: [], open: false, loading: false }
    },

    // PAYMENT
    payment: {
        status: "unpaid",
        paid_amount: 0
    },

    // SUMMARY
    summary: {
        total_amount: 0,
        debt_amount: 0
    },

    // META
    meta: {
        id: null,
        code: null,
        status: "draft",
        description: "",
        created_at: null,
        updated_at: null
    },

    // UI
    ui: {
        loading: false
    }

};