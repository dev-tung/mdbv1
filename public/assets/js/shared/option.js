const Option = {
	payment: {
		unpaid: {
			label: 'Chưa thanh toán',
			color: 'danger',
		},
		partial: {
			label: 'Thanh toán một phần',
			color: 'danger',
		},
		paid: {
			label: 'Đã thanh toán',
			color: 'default',
		},
	},

	process: {
		pending: {
			label: 'Chờ xử lý',
			color: 'danger',
		},
		completed: {
			label: 'Đã hoàn thành',
			color: 'default',
		},
	},
	product: {
		active: {
			label: 'Đang bán',
		},

		inactive: {
			label: 'Ngừng bán',
		},
	},
};

export default Option;
