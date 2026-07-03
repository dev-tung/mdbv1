import State from './State.js';

const Renderer = {

    warehouses() {

        const select = document.getElementById('warehouse_id');

        select.innerHTML = '';
        
        State.warehouse.list.forEach(warehouse => {

            const option = document.createElement('option');

            option.value = warehouse.id;
            option.textContent = warehouse.name;
            option.selected = warehouse.id == State.warehouse.id;

            select.appendChild(option);

        });

    },

    products() {

        const tbody = document.getElementById('selected_products');

        tbody.innerHTML = '';

        State.product.selected.forEach((product, index) => {

            tbody.insertAdjacentHTML('beforeend', `
                <tr data-index="${index}">

                    <td>${product.name}</td>

                    <td width="120">
                        <input
                            type="number"
                            class="form-control quantity"
                            value="${product.quantity}"
                            min="1">
                    </td>

                    <td width="180">
                        <input
                            type="number"
                            class="form-control purchase-price"
                            value="${product.purchase_price}"
                            min="0">
                    </td>

                    <td width="180">
                        ${product.total_amount.toLocaleString()} ₫
                    </td>

                    <td width="80">
                        <button
                            type="button"
                            class="btn btn-sm btn-danger remove-product">
                            Xóa
                        </button>
                    </td>

                </tr>
            `);

        });

    },

    summary() {

        document.getElementById('total_amount').textContent =
            State.summary.total_amount.toLocaleString();

        document.getElementById('paid_amount_view').textContent =
            State.summary.paid_amount.toLocaleString();

        document.getElementById('debt_amount_view').textContent =
            State.summary.debt_amount.toLocaleString();

    },

    supplierSuggestions() {

        const box = document.getElementById('supplier_suggestions');

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

    productSuggestions() {

        const box = document.getElementById('product_suggestions');

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

    purchase() {

        document.getElementById('description').value =
            State.purchase.description;

        document.getElementById('status').value =
            State.purchase.status;

        document.getElementById('payment').value =
            State.purchase.payment;

        document.getElementById('paid_amount').value =
            State.purchase.paid_amount;

    }

};

export default Renderer;