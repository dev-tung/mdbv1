import State from './State.js';

const Renderer = {
    init() {
        this.order();

        this.products();

        this.summary();

        this.payment();

        this.customerSuggestions();

        this.productSuggestions();
    },

    /* =================================================
       ORDER
    ================================================= */

    order() {
        document.querySelector('#customer_search').value = State.order.customer_name ?? '';

        document.querySelector('#customer_id').value = State.order.customer_id ?? '';

        document.querySelector('#description').value = State.order.description ?? '';

        document.querySelector('#status').value = State.order.status ?? 'draft';

        document.querySelector('#payment').value = State.order.payment ?? 'unpaid';

        document.querySelector('#paid_amount').value = State.order.paid_amount ?? 0;

        document.querySelector('#vat_rate').value = State.order.vat_rate ?? 0;

        const wrapper = document.querySelector('#paid_amount_wrapper');

        wrapper.classList.toggle(
            'd-none',

            !['partial', 'credit'].includes(State.order.payment),
        );
    },

    /* =================================================
       CUSTOMER SUGGESTIONS
    ================================================= */

    customerSuggestions() {
        const box = document.querySelector('#customer_suggestions');

        if (!box) return;

        box.innerHTML = '';

        if (!State.customer.suggestions.length) {
            box.classList.add('d-none');

            return;
        }

        State.customer.suggestions.forEach((item) => {
            box.insertAdjacentHTML(
                'beforeend',
                `

                <button
                    type="button"
                    class="list-group-item list-group-item-action customer-item"
                    data-id="${item.id}">

                    ${item.name}

                </button>

            `,
            );
        });

        box.classList.remove('d-none');
    },

    /* =================================================
       PRODUCT SUGGESTIONS
    ================================================= */

    productSuggestions() {
        const box = document.querySelector('#product_suggestions');

        if (!box) return;

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
                    data-id="${item.product_id}">

                    ${item.product_name} ${Number(item.vat_rate) > 0 ? '(VAT)' : ''}

                </button>
                `,
            );
        });

        box.classList.remove('d-none');
    },

    /* =================================================
       PRODUCTS
    ================================================= */

    products() {
        const tbody = document.querySelector('#selected_products');

        if (!tbody) return;

        tbody.innerHTML = '';

        State.order.items.forEach((item, index) => {
            tbody.insertAdjacentHTML(
                'beforeend',
                `

              <tr data-index="${index}">

                  <!-- NAME -->

                  <td>

                    ${item.product_name}
                    <input
                        type="hidden"
                        class="purchase-id"
                        value="${item.purchase_id ?? ''}">

                  </td>


                  <!-- QUANTITY -->

                  <td>

                      <input
                          type="number"
                          class="form-control quantity"
                          value="${item.quantity ?? 0}"
                          min="1">

                  </td>


                  <!-- SELLING PRICE -->

                  <td>

                      <input
                          type="number"
                          class="form-control selling-price"
                          value="${item.selling_price ?? 0}"
                          min="0"
                          step="0.01"
                          ${item.is_gift ? 'disabled' : ''}>

                  </td>


                  <!-- GIFT -->

                  <td class="text-center">

                      <input
                          type="checkbox"
                          class="form-check-input is-gift"
                          ${item.is_gift ? 'checked' : ''}>

                  </td>


                  <!-- SUBTOTAL -->

                  <td class="subtotal-amount">

                      ${this.money(item.subtotal_amount)}

                  </td>


                  <!-- VAT -->

                  <td class="item-vat">

                      ${this.money(item.vat_amount)}

                  </td>


                  <!-- TOTAL -->

                  <td class="item-total">

                      ${this.money(item.total_amount)}

                  </td>


                  <!-- REMOVE -->

                  <td>

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

        const item = State.order.items[index];

        row.querySelector('.subtotal-amount').textContent = this.money(item.subtotal_amount);

        row.querySelector('.item-vat').textContent = this.money(item.vat_amount);

        row.querySelector('.item-total').textContent = this.money(item.total_amount);
    },

    /* =================================================
       SUMMARY
    ================================================= */

    summary() {
        document.querySelector('#subtotal_amount').textContent = this.money(State.order.subtotal_amount);

        document.querySelector('#vat_amount').textContent = this.money(State.order.vat_amount);

        document.querySelector('#total_amount').textContent = this.money(State.order.total_amount);

        document.querySelector('#debt_amount').textContent = this.money(State.order.debt_amount);
    },

    /* =================================================
       PAYMENT
    ================================================= */

    payment() {
        const wrapper = document.querySelector('#paid_amount_wrapper');

        if (!wrapper) return;

        wrapper.classList.toggle(
            'd-none',

            !['partial', 'credit'].includes(State.order.payment),
        );
    },

    /* =================================================
       MONEY
    ================================================= */

    money(value) {
        return Number(value || 0).toLocaleString('vi-VN');
    },
};

export default Renderer;
