// =========================================================
// modules/shop/state/PurchaseState.js
// =========================================================

/**
 * =========================================================
 * PURCHASE DETAIL
 * Dùng cho:
 * - Create Purchase
 * - Edit Purchase
 * - Show Purchase
 * =========================================================
 */
export const PurchaseDetailState = {

    // =====================================================
    // SUPPLIER
    // =====================================================

    supplier: {
        id: null,
        name: ""
    },

    // =====================================================
    // WAREHOUSE
    // =====================================================

    warehouse: {
        id: null,
        name: ""
    },

    // =====================================================
    // PRODUCTS
    // =====================================================

    products: [
        /*
        {
            product_id: 1,
            name: "IPhone 16",

            quantity: 1,
            price: 25000000,

            subtotal: 25000000
        }
        */
    ],

    // =====================================================
    // PAYMENT
    // =====================================================

    payment: {
        method: "cash",          // cash | transfer
        status: "unpaid",        // unpaid | partial | paid
        paid_amount: 0
    },

    // =====================================================
    // SUMMARY
    // (Computed - luôn tính lại từ products)
    // =====================================================

    summary: {
        total_amount: 0,
        debt_amount: 0
    },

    // =====================================================
    // META
    // =====================================================

    meta: {
        id: null,
        code: null,

        status: "draft",

        description: "",

        created_at: null,
        updated_at: null
    },

    // =====================================================
    // UI STATE
    // (Không gửi lên API)
    // =====================================================

    ui: {

        loading: false,

        supplier_search: "",

        product_search: "",

        supplier_dropdown: false,

        product_dropdown: false
    }

};



/**
 * =========================================================
 * PURCHASE LIST
 * Dùng cho:
 * - Purchase Index
 * =========================================================
 */
export const PurchaseListState = {

    // =====================================================
    // FILTER
    // =====================================================

    filters: {

        keyword: "",

        supplier_id: null,

        warehouse_id: null,

        payment: null,

        status: null,

        from_date: null,

        to_date: null
    },

    // =====================================================
    // DATA
    // =====================================================

    purchases: [],

    // =====================================================
    // SUMMARY
    // (Computed sau khi load danh sách)
    // =====================================================

    summary: {

        total_purchase: 0,

        total_amount: 0,

        total_paid_amount: 0,

        total_debt_amount: 0
    },

    // =====================================================
    // PAGINATION
    // =====================================================

    pagination: {

        page: 1,

        per_page: 10,

        total: 0,

        total_pages: 0
    },

    // =====================================================
    // UI
    // =====================================================

    ui: {

        loading: false,

        selected_ids: []
    }

};