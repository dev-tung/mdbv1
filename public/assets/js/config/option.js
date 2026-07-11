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

    order: {
        pending: {
            label: 'Chờ xử lý',
            color: 'warning',
        },
        completed: {
            label: 'Đã hoàn thành',
            color: 'success',
        },
    },

    price: {
        under1  : 'Dưới 1 triệu',
        fro1To3 : '1 - 3 triệu',
        fro3To5 : '3 - 5 triệu',
        over5   : 'Trên 5 triệu'
    }
};

export default Option;