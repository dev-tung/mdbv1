import http from '/assets/js/common/http.js';

export async function getWarehouses() {

    return await http.get('/warehouse/list');

}

export async function getPurchase(purchaseId) {

    return await http.get(`/purchase/${purchaseId}`);

}

export async function createPurchase(data) {

    return await http.post('/purchase', data);

}

export async function updatePurchase(purchaseId, data) {

    return await http.put(`/purchase/${purchaseId}`, data);

}

export async function deletePurchase(purchaseId) {

    return await http.delete(`/purchase/${purchaseId}`);

}

export async function searchProducts(keyword = '') {

    return await http.get('/product/search', {
        keyword
    });

}