// public/assets/js/helpers/api.js

export const Api = {

    async get(url) {

        try {

            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            return await response.json();

        } catch (e) {

            console.error(e);

            return {
                success: false,
                message: 'Có lỗi xảy ra.'
            };
        }
    },

    async post(url, data = {}) {

        try {

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });

            return await response.json();

        } catch (e) {

            console.error(e);

            return {
                success: false,
                message: 'Có lỗi xảy ra.'
            };
        }
    },

    async put(url, data = {}) {

        try {

            const response = await fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });

            return await response.json();

        } catch (e) {

            console.error(e);

            return {
                success: false,
                message: 'Có lỗi xảy ra.'
            };
        }
    },

    async delete(url) {

        try {

            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            return await response.json();

        } catch (e) {

            console.error(e);

            return {
                success: false,
                message: 'Có lỗi xảy ra.'
            };
        }
    }

};