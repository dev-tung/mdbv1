import Api from '/assets/js/common/api.js';

const BASE_URL = '/api/purchases';

export const PurchaseApi = {

    async getList(params = {}) {
        return await Api.get(BASE_URL, params);
    },

    async getById(id) {
        return await Api.get(`${BASE_URL}/${id}`);
    },

    async create(data) {
        return await Api.post(BASE_URL, data);
    },

    async update(id, data) {
        return await Api.put(`${BASE_URL}/${id}`, data);
    },

    async delete(id) {
        return await Api.delete(`${BASE_URL}/${id}`);
    },

    async updatePayment(id, data) {
        return await Api.patch(`${BASE_URL}/${id}/payment`, data);
    }

};