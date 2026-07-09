// =========================================================
// common/http.js
// =========================================================

const http = {
    async get(url, params = {}) {
        const query = new URLSearchParams(params).toString();

        const response = await fetch(query ? `${url}?${query}` : url);

        return await response.json();
    },

    async post(url, data = {}) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();

        return result;
    },

    async put(url, data = {}) {
        const response = await fetch(url, {
            method: 'PUT',

            headers: {
                'Content-Type': 'application/json',
            },

            body: JSON.stringify(data),
        });

        return await response.json();
    },

    async patch(url, data = {}) {
        const response = await fetch(url, {
            method: 'PATCH',

            headers: {
                'Content-Type': 'application/json',
            },

            body: JSON.stringify(data),
        });

        return await response.json();
    },

    async delete(url) {
        const response = await fetch(url, {
            method: 'DELETE',
        });

        return await response.json();
    },
};

export default http;
