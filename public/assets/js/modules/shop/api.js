import http from "/assets/js/common/helper/http.js";

export const api = {

    // =====================================================
    // PURCHASE
    // =====================================================

    purchase: {

        async getList(params = {}) {
            return await http.get("/api/purchases", params);
        },

        async getById(id) {
            return await http.get(`/api/purchases/${id}`);
        },

        async create(data) {
            return await http.post("/api/purchases", data);
        },

        async update(id, data) {
            return await http.put(`/api/purchases/${id}`, data);
        },

        async delete(id) {
            return await http.delete(`/api/purchases/${id}`);
        },

        async updatePayment(id, data) {
            return await http.patch(`/api/purchases/${id}/payment`, data);
        }

    },

    // =====================================================
    // PRODUCT
    // =====================================================

    product: {

        async getList(params = {}) {
            return await http.get("/api/products", params);
        },

        async getById(id) {
            return await http.get(`/api/products/${id}`);
        }

    },

    // =====================================================
    // SUPPLIER
    // =====================================================

    supplier: {

        async getList(params = {}) {
            return await http.get("/api/suppliers", params);
        },

        async getById(id) {
            return await http.get(`/api/suppliers/${id}`);
        }

    },

    // =====================================================
    // WAREHOUSE
    // =====================================================

    warehouse: {

        async getList(params = {}) {
            return await http.get("/api/warehouses", params);
        },

        async getById(id) {
            return await http.get(`/api/warehouses/${id}`);
        }

    }

};