export const Api = {

    async get(url) {

        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`GET ${url} failed: ${response.status}`);
        }

        return await response.json();
    },

    async post(url, data = {}) {

        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`POST ${url} failed: ${response.status}`);
        }

        return await response.json();
    },

    async put(url, data = {}) {

        const response = await fetch(url, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`PUT ${url} failed: ${response.status}`);
        }

        return await response.json();
    },

    async delete(url, data = {}) {

        const formData = new FormData();

        Object.entries(data).forEach(([key, value]) => {
            formData.append(key, value);
        });

        const response = await fetch(url, {
            method: "POST",
            body: formData
        });

        if (!response.ok) {
            throw new Error(`DELETE ${url} failed: ${response.status}`);
        }

        return await response.json();
    }

};