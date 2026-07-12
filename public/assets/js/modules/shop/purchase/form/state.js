const State = {
    purchase: {
        id: 1,
        supplier_id: 2,
        supplier_name: 'Công ty TNHH Yonex Việt Nam',
        description: 'Nhập hàng tháng 07',
        status: 'pending',
        warehouse_id: 1,
        vat_rate: 8,
        payment: 'partial',
        paid_amount: 5000000,
    },

    warehouses: {
        1: {
            label: 'Kho Hà Nội',
        },
        2: {
            label: 'Kho Hưng Yên',
        }
    },

    items: [
        {
            id: 1,
            name: 'Yonex Astrox 100ZZ',
            quantity: 2,
            purchase_price: 4200000,
            selling_price: 4900000,
            subtotal: 8400000,
            tax: 672000,
            total: 9072000,
        },
        {
            id: 2,
            name: 'Lining Axforce 90',
            quantity: 3,
            purchase_price: 3100000,
            selling_price: 3600000,
            subtotal: 9300000,
            tax: 744000,
            total: 10044000,
        },
    ],

    summary: {
        subtotal: 17700000,
        tax: 1416000,
        total: 19116000,
        debt: 14116000,
    },

    setSupplier(supplier) {
        this.purchase.supplier_id = supplier.id;
        this.purchase.supplier_name = supplier.name;
    },
};

export default State;