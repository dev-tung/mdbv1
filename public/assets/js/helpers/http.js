// =========================================================
// helpers/http.js
// =========================================================

const Http = {
	/* =================================================
	   REQUEST
	================================================= */

	async request(method, url, data = null) {
		const options = {
			method,
		};

		if (data !== null) {
			const { body, headers } = this.buildBody(data);

			options.body = body;

			if (Object.keys(headers).length) {
				options.headers = headers;
			}
		}

		const response = await fetch(url, options);

		return await response.json();
	},

	/* =================================================
	   BODY
	================================================= */

	buildBody(data) {
		if (this.hasFile(data)) {
			const formData = new FormData();

			Object.entries(data).forEach(([key, value]) => {
				if (value !== undefined && value !== null) {
					formData.append(key, value);
				}
			});

			return {
				body: formData,
				headers: {},
			};
		}

		return {
			body: JSON.stringify(data),
			headers: {
				'Content-Type': 'application/json',
			},
		};
	},

	/* =================================================
	   FILE
	================================================= */

	hasFile(data) {
		return Object.values(data).some((value) => value instanceof File);
	},

	/* =================================================
	   GET
	================================================= */

	get(url, params = {}) {
		const query = new URLSearchParams(params).toString();

		return this.request('GET', query ? `${url}?${query}` : url);
	},

	/* =================================================
	   POST
	================================================= */

	post(url, data = {}) {
		return this.request('POST', url, data);
	},

	/* =================================================
	   PUT
	================================================= */

	put(url, data = {}) {
		return this.request('PUT', url, data);
	},

	/* =================================================
	   PATCH
	================================================= */

	patch(url, data = {}) {
		return this.request('PATCH', url, data);
	},

	/* =================================================
	   DELETE
	================================================= */

	delete(url) {
		return this.request('DELETE', url);
	},
};

export default Http;
