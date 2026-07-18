const State = {
	filters: {
		date_from: '',
		date_to: '',
		supplier_id: '',
		payment: '',
	},

	suppliers: {},

	purchases: [],

	summary: {
		total_amount: 0,
		paid_amount: 0,
		debt_amount: 0,
	},

	pagination: {
		page: 1,
		limit: 20,
		total: 0,
		total_pages: 1,
	},

	setSuppliers(suppliers = []) {
		return Object.fromEntries(
			suppliers.map(({ id, name }) => [
				id,
				{
					label: name,
				},
			]),
		);
	},

	setDefault(data) {
		this.suppliers = this.setSuppliers(data.suppliers || []);
		this.purchases = data.purchases || [];

		this.pagination = {
			...this.pagination,
			...(data.pagination || {}),
		};

		this.setSummary();
	},

	setSummary() {
		const total_amount = this.purchases.reduce((sum, purchase) => sum + Number(purchase.total_amount || 0), 0);

		const paid_amount = this.purchases.reduce((sum, purchase) => sum + Number(purchase.paid_amount || 0), 0);

		const debt_amount = this.purchases.reduce((sum, purchase) => sum + Number(purchase.debt_amount || 0), 0);

		this.summary = {
			total_amount,
			paid_amount,
			debt_amount,
		};
	},
};

export default State;
