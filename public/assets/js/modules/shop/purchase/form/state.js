const State = {

    purchase: {
        id: 1,
        supplier_id: 2,
        supplier_name: 'Công ty TNHH Yonex Việt Nam',
        description: 'Nhập hàng tháng 07',
        status: 'draft',
        warehouse_id: 1,
        vat_rate: 8,
        payment: 'partial',
        paid_amount: 5000000,
    },

    items: [
        {
            id: 1,
            name: 'Yonex Astrox 100ZZ',
            quantity: 2,
            purchase_price: 4200000,
            selling_price: 4900000,
        },
        {
            id: 2,
            name: 'Lining Axforce 90',
            quantity: 3,
            purchase_price: 3100000,
            selling_price: 3600000,
        }
    ]

};

export default State;