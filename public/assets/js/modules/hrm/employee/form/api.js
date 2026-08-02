import Http from '/assets/js/helpers/http.js';

const Api = {
	/* =================================================
	   EMPLOYEE
	================================================= */

	createEmployee(data) {
		return Http.post('/api/employees', data);
	},

	updateEmployee(data) {
		return Http.post(`/api/employees/update/${data.id}`, data);
	},

	getEmployee(id) {
		return Http.get(`/api/employees/show/${id}`);
	},
};

export default Api;
