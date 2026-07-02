import Http from '/assets/js/common/http.js';

const BASE_URL = '/api/products';

export const ProductApi = {

    async getList(params = {}) {
        return await Http.get(BASE_URL, params);
    },

    async getById(id) {
        return await Http.get(`${BASE_URL}/${id}`);
    },

    async create(data) {
        return await Http.post(BASE_URL, data);
    },

    async update(id, data) {
        return await Http.put(`${BASE_URL}/${id}`, data);
    },

    async delete(id) {
        return await Http.delete(`${BASE_URL}/${id}`);
    },

    async updatePayment(id, data) {
        return await Http.patch(`${BASE_URL}/${id}/payment`, data);
    }

};