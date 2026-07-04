import State from './State.js';

const Renderer = {

    init() {

        this.purchase();
        this.warehouses();
        this.products();
        this.summary();
        this.payment();
        this.supplierSuggestions();
        this.productSuggestions();

    },

    /* =================================================
       PURCHASE
    ================================================= */

    purchase() {

        document.querySelector('#supplier_id').value =
            State.purchase.supplier_id ?? '';

        document.querySelector('#description').value =
            State.purchase.description ?? '';

        document.querySelector('#status').value =
            State.purchase.status ?? '';

        document.querySelector('#warehouse_id').value =
            State.purchase.warehouse_id ?? '';

        document.querySelector('#payment').value =
            State.purchase.payment ?? '';

        document.querySelector('#paid_amount').value =
            State.purchase.paid_amount ?? 0;

    },

    /* =================================================
    WAREHOUSE
    ================================================= */

    warehouses() {

        const select = document.querySelector('#warehouse_id');

        select.innerHTML = `
            <option value="">-- Chọn kho --</option>
        `;

        State.warehouse.list.forEach(warehouse => {

            select.insertAdjacentHTML('beforeend', `
                <option
                    value="${warehouse.id}"
                    ${warehouse.id == State.purchase.warehouse_id ? 'selected' : ''}>
                    ${warehouse.name}
                </option>
            `);

        });

    },

    /* =================================================
       SUPPLIER
    ================================================= */

    supplierSuggestions() {

        const box = document.querySelector('#supplier_suggestions');

        box.innerHTML = '';

        if (!State.supplier.suggestions.length) {

            box.classList.add('d-none');

            return;

        }

        State.supplier.suggestions.forEach(item => {

            box.insertAdjacentHTML('beforeend', `

                <button
                    type="button"
                    class="list-group-item list-group-item-action supplier-item"
                    data-id="${item.id}">

                    ${item.name}

                </button>

            `);

        });

        box.classList.remove('d-none');

    },

    /* =================================================
       PRODUCT
    ================================================= */

    productSuggestions() {

        const box = document.querySelector('#product_suggestions');

        box.innerHTML = '';

        if (!State.product.suggestions.length) {

            box.classList.add('d-none');

            return;

        }

        State.product.suggestions.forEach(item => {

            box.insertAdjacentHTML('beforeend', `

                <button
                    type="button"
                    class="list-group-item list-group-item-action product-item"
                    data-id="${item.id}">

                    ${item.name}

                </button>

            `);

        });

        box.classList.remove('d-none');

    },

    products() {

        const tbody = document.querySelector('#selected_products');

        tbody.innerHTML = '';

        State.purchase.items.forEach((item, index) => {

            tbody.insertAdjacentHTML('beforeend', `

                <tr data-index="${index}">

                    <td>${item.name}</td>

                    <td width="60">

                        <input
                            type="number"
                            class="form-control quantity"
                            value="${item.quantity}"
                            min="1">

                    </td>

                    <td width="120">

                        <input
                            type="number"
                            class="form-control purchase-price"
                            value="${item.purchase_price}"
                            min="0">

                    </td>

                    <td width="120">

                        <input
                            type="number"
                            class="form-control order-price"
                            value="${item.order_price}"
                            min="0">

                    </td>

                    <td width="120">${item.total_amount}</td>

                    <td width="60">

                        <input
                            type="number"
                            class="form-control vat-amount"
                            value="${item.vat_rate}"
                            min="0">

                    </td>

                    <td width="120">${item.vat_amount}</td>

                    <td width="120">${item.total_amount_with_vat}</td>

                    <td width="60">

                        <button
                            type="button"
                            class="btn btn-sm btn-danger btn-remove">
                            Xóa
                        </button>

                    </td>

                </tr>

            `);

        });

    },

    /* =================================================
       SUMMARY
    ================================================= */

    summary() {

        document.querySelector('#total_amount').textContent =
            State.purchase.total_amount.toLocaleString();

        document.querySelector('#paid_amount_view').textContent =
            State.purchase.paid_amount.toLocaleString();

        document.querySelector('#debt_amount_view').textContent =
            State.purchase.debt_amount.toLocaleString();

    },

    /* =================================================
       PAYMENT
    ================================================= */

    payment() {

        const wrapper = document.querySelector('#paid_amount_wrapper');

        if (
            State.purchase.payment === 'partial'
            || State.purchase.payment === 'credit'
        ) {

            wrapper.classList.remove('d-none');

        } else {

            wrapper.classList.add('d-none');

        }

    }

};

export default Renderer;