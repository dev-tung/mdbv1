const Option = {
	payment: {
		unpaid: {
			label: 'Chưa thanh toán',
			color: 'danger',
		},
		partial: {
			label: 'Thanh toán một phần',
			color: 'warning',
		},
		paid: {
			label: 'Đã thanh toán',
			color: 'success',
		},
	},

	process: {
		pending: {
			label: 'Chờ xử lý',
			color: 'warning',
		},
		completed: {
			label: 'Đã hoàn thành',
			color: 'success',
		},
	}
};

export default Option;
