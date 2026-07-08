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
        document.querySelector('#supplier_search').value = State.purchase.supplier_name ?? '';

        document.querySelector('#supplier_id').value = State.purchase.supplier_id ?? '';

        document.querySelector('#warehouse_id').value = State.purchase.warehouse_id ?? '';

        document.querySelector('#description').value = State.purchase.description ?? '';

        document.querySelector('#status').value = State.purchase.status ?? 'draft';

        document.querySelector('#payment').value = State.purchase.payment ?? 'unpaid';

        document.querySelector('#paid_amount').value = State.purchase.paid_amount ?? 0;

        document.querySelector('#vat_rate').value = State.purchase.vat_rate ?? 0;

        const wrapper = document.querySelector('#paid_amount_wrapper');

        wrapper.classList.toggle('d-none', State.purchase.payment !== 'partial');

        // Ẩn nút submit nếu là form sửa
        const submitButton = document.querySelector('#btn-submit');

        if (submitButton) {
            submitButton.classList.toggle('d-none', !!State.purchase.id);
        }
    },

    /* =================================================
       WAREHOUSE
    ================================================= */

    warehouses() {
        const select = document.querySelector('#warehouse_id');

        select.innerHTML = `
            <option value="">-- Chọn kho --</option>
        `;

        State.warehouse.list.forEach((warehouse) => {
            select.insertAdjacentHTML(
                'beforeend',
                `
                <option
                    value="${warehouse.id}"
                    ${warehouse.id == State.purchase.warehouse_id ? 'selected' : ''}>
                    ${warehouse.name}
                </option>
            `,
            );
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

        State.supplier.suggestions.forEach((item) => {
            box.insertAdjacentHTML(
                'beforeend',
                `

                <button
                    type="button"
                    class="list-group-item list-group-item-action supplier-item"
                    data-id="${item.id}">

                    ${item.name}

                </button>

            `,
            );
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

        State.product.suggestions.forEach((item) => {
            box.insertAdjacentHTML(
                'beforeend',
                `

                <button
                    type="button"
                    class="list-group-item list-group-item-action product-item"
                    data-id="${item.id}">

                    ${item.name}

                </button>

            `,
            );
        });

        box.classList.remove('d-none');
    },

    products() {
        const tbody = document.querySelector('#selected_products');

        tbody.innerHTML = '';

        State.purchase.items.forEach((item, index) => {
            tbody.insertAdjacentHTML(
                'beforeend',
                `

                <tr data-index="${index}">

                    <!-- NAME -->
                    <td>${item.product_name}</td>

                    <!-- QUANTITY -->
                    <td width="80">
                        <input
                            type="number"
                            class="form-control quantity"
                            value="${item.quantity || 0}"
                            min="1">
                    </td>

                    <!-- PURCHASE PRICE -->
                    <td width="140">
                        <input
                            type="number"
                            class="form-control purchase-price"
                            value="${item.purchase_price || 0}"
                            min="0"
                            step="0.01">
                    </td>

                    <!-- SELLING PRICE -->
                    <td width="140">
                        <input
                            type="number"
                            class="form-control selling_price"
                            value="${item.selling_price || 0}"
                            min="0"
                            step="0.01">
                    </td>

                    <!-- SUBTOTAL -->
                    <td class="subtotal-amount" width="150">
                        ${Number(item.subtotal_amount || 0).toLocaleString('vi-VN')}
                    </td>

                    <!-- VAT -->
                    <td class="item-vat" width="150">
                        ${Number(item.vat_amount || 0).toLocaleString('vi-VN')}
                    </td>

                    <!-- TOTAL -->
                    <td class="item-total" width="150">
                        ${Number(item.total_amount_with_vat || 0).toLocaleString('vi-VN')}
                    </td>

                    <!-- REMOVE -->
                    <td width="60">
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger btn-remove">
                            Xóa
                        </button>
                    </td>

                </tr>

            `,
            );
        });
    },

    productsUpdate(index) {
        const row = document.querySelector(`tr[data-index="${index}"]`);

        if (!row) return;

        const item = State.purchase.items[index];

        row.querySelector('.subtotal-amount').textContent = Number(item.subtotal_amount || 0).toLocaleString('vi-VN');

        row.querySelector('.item-vat').textContent = Number(item.vat_amount || 0).toLocaleString('vi-VN');

        row.querySelector('.item-total').textContent = Number(item.total_amount_with_vat || 0).toLocaleString('vi-VN');
    },

    /* =================================================
       SUMMARY
    ================================================= */

    summary() {
        document.querySelector('#subtotal_amount').textContent = Number(
            State.purchase.subtotal_amount || 0,
        ).toLocaleString('vi-VN');

        document.querySelector('#vat_amount').textContent = Number(State.purchase.vat_amount || 0).toLocaleString(
            'vi-VN',
        );

        document.querySelector('#total_amount').textContent = Number(State.purchase.total_amount || 0).toLocaleString(
            'vi-VN',
        );

        document.querySelector('#debt_amount').textContent = Number(State.purchase.debt_amount || 0).toLocaleString(
            'vi-VN',
        );
    },

    /* =================================================
       PAYMENT
    ================================================= */

    payment() {
        const wrapper = document.querySelector('#paid_amount_wrapper');

        if (State.purchase.payment === 'partial' || State.purchase.payment === 'credit') {
            wrapper.classList.remove('d-none');
        } else {
            wrapper.classList.add('d-none');
        }
    },
};

export default Renderer;
