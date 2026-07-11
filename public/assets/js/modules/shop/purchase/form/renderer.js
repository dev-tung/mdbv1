import State from './state.js';

const $ = (selector) => document.querySelector(selector);

const format = (number) =>
    Number(number).toLocaleString('vi-VN');

const Renderer = {

    render() {

        const purchase = State.purchase;

        $('#purchase_id').value = purchase.id;

        $('#supplier_id').value = purchase.supplier_id;
        $('#supplier_search').value = purchase.supplier_name;

        $('#description').value = purchase.description;

        $('#status').value = purchase.status;

        $('#warehouse_id').value = purchase.warehouse_id;

        $('#vat_rate').value = purchase.vat_rate;

        $('#payment').value = purchase.payment;

        $('#paid_amount').value = purchase.paid_amount;

        this.renderProducts();

    },

    renderProducts() {

        const tbody = $('#selected_products');

        tbody.innerHTML = '';

        let subtotal = 0;
        let vat = 0;
        let total = 0;

        State.items.forEach((item, index) => {

            const row = document
                .getElementById('purchase-item-template')
                .content
                .cloneNode(true);

            const tr = row.querySelector('tr');

            tr.dataset.index = index;

            const money = item.quantity * item.purchase_price;
            const vatMoney = money * State.purchase.vat_rate / 100;
            const totalMoney = money + vatMoney;

            subtotal += money;
            vat += vatMoney;
            total += totalMoney;

            tr.querySelector('.product-name').textContent = item.name;

            tr.querySelector('.quantity').value = item.quantity;

            tr.querySelector('.purchase-price').value = item.purchase_price;

            tr.querySelector('.selling-price').value = item.selling_price;

            tr.querySelector('.subtotal').textContent = format(money);

            tr.querySelector('.vat').textContent = format(vatMoney);

            tr.querySelector('.total').textContent = format(totalMoney);

            tbody.appendChild(row);

        });

        $('#subtotal_amount').textContent = format(subtotal);

        $('#vat_amount').textContent = format(vat);

        $('#total_amount').textContent = format(total);

        $('#debt_amount').textContent = format(
            total - State.purchase.paid_amount
        );

    }

};

export default Renderer;