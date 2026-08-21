import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   EMPLOYEE
	================================================= */

	async getEmployees(params = {}) {
		return await Http.get('/api/employees', params);
	},

	async deleteEmployee(id) {
		return await Http.post(`/api/employees/delete/${id}`);
	},
};

export default Api;
