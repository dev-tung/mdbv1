import State from './State.js';

const Renderer = {
    /* =================================================
       MAIN
    ================================================= */

    render() {
        this.renderFilters();

        this.renderSummary();

        this.renderTable();

        this.renderPagination();
    },

    /* =================================================
       FILTER
    ================================================= */

    renderFilters() {
        this.renderSuppliers();

        this.renderPayments();
    },

    renderSuppliers() {
        const select = document.querySelector('#filter-supplier');

        if (!select) return;

        select.innerHTML = `

            <option value="">
                Nhà cung cấp
            </option>


            ${(State.suppliers || [])
                .map(
                    item => `

                    <option
                        value="${item.id}"
                        ${State.filter.supplier_id == item.id ? 'selected' : ''}
                    >
                        ${item.name}
                    </option>

                `
                )
                .join('')}

        `;
    },

    renderPayments() {
        const select = document.querySelector('#filter-payment');

        if (!select) return;

        select.innerHTML = `

            <option value="">
                Thanh toán
            </option>


            ${this.renderOptions(State.options?.payments || {}, State.filter.payment)}

        `;
    },

    /* =================================================
       SUMMARY
    ================================================= */

    renderSummary() {
        const summary = State.summary || {};

        const total = document.querySelector('#sum-total-amount');

        if (total) {
            total.textContent = this.money(summary.total_amount);
        }

        const paid = document.querySelector('#sum-paid-amount');

        if (paid) {
            paid.textContent = this.money(summary.paid_amount);
        }

        const debt = document.querySelector('#sum-debt-amount');

        if (debt) {
            debt.textContent = this.money(summary.debt_amount);
        }
    },

    /* =================================================
       TABLE
    ================================================= */

    renderTable() {
        const tbody = document.querySelector('#purchase-table-body');

        if (!tbody) return;

        if (!State.purchases?.length) {
            tbody.innerHTML = `

                <tr>

                    <td
                        colspan="10"
                        class="text-center text-muted"
                    >

                        Không có dữ liệu

                    </td>

                </tr>

            `;

            return;
        }

        tbody.innerHTML = State.purchases
            .map(
                (purchase, index) => `


            <tr>


                <td>
                    ${index + 1}
                </td>



                <td>
                    ${purchase.supplier_name ?? ''}
                </td>



                <td>
                    ${purchase.warehouse_name ?? ''}
                </td>



                <td>
                    ${this.money(purchase.total_amount)}
                </td>



                <td>
                    ${this.money(purchase.paid_amount)}
                </td>



                <td>
                    ${this.money(purchase.debt_amount)}
                </td>



                <!-- STATUS -->

                <td>

                    <select

                        class="
                            form-select
                            form-select-sm
                            purchase-status
                            text-${this.getColor(State.options?.statuses, purchase.status)}
                        "

                        data-id="${purchase.id}"

                    >

                        ${this.renderOptions(State.options?.statuses || {}, purchase.status)}


                    </select>


                </td>




                <!-- PAYMENT -->

                <td>


                    <select

                        class="
                            form-select
                            form-select-sm
                            purchase-payment
                            text-${this.getColor(State.options?.payments, purchase.payment)}
                        "

                        data-id="${purchase.id}"

                    >


                        ${this.renderOptions(State.options?.payments || {}, purchase.payment)}


                    </select>


                </td>




                <td>
                    ${purchase.created_at ?? ''}
                </td>




                <td>


                    <a

                        href="/admin/purchases/edit/${purchase.id}"

                        class="
                            btn
                            btn-sm
                            btn-outline-secondary
                        "

                    >

                        View

                    </a>


                </td>



            </tr>


        `
            )
            .join('');
    },

    /* =================================================
       OPTIONS
    ================================================= */

    renderOptions(data = {}, selected) {
        return Object.entries(data)

            .map(
                ([value, item]) => `


                <option

                    value="${value}"

                    ${value == selected ? 'selected' : ''}

                >

                    ${item.label}

                </option>


            `
            )

            .join('');
    },

    getColor(data = {}, value) {
        return data?.[value]?.color || '';
    },

    /* =================================================
       PAGINATION
    ================================================= */

    renderPagination() {
        // làm sau
    },

    /* =================================================
       MONEY
    ================================================= */

    money(value) {
        return Number(value || 0).toLocaleString('vi-VN');
    },
};

export default Renderer;
