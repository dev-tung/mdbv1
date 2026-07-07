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

        this.renderCustomers();

        this.renderPayments();

    },


    renderCustomers() {

        const select =
            document.querySelector('#filter-customer');

        if (!select) return;

        select.innerHTML = `

            <option value="">
                Khách hàng
            </option>

            ${
                (State.customers || [])
                .map(item => `

                    <option
                        value="${item.id}"
                        ${State.filter.customer_id == item.id
                            ? 'selected'
                            : ''
                        }
                    >
                        ${item.name}
                    </option>

                `)
                .join('')
            }

        `;

    },


    renderPayments() {

        const select =
            document.querySelector('#filter-payment');

        if (!select) return;

        select.innerHTML = `

            <option value="">
                Thanh toán
            </option>

            ${
                this.renderOptions(
                    State.options?.payments || {},
                    State.filter.payment
                )
            }

        `;

    },


    /* =================================================
       SUMMARY
    ================================================= */

    renderSummary() {

        const summary =
            State.summary || {};

        const total =
            document.querySelector('#sum-total-amount');

        if (total) {

            total.textContent =
                this.money(summary.total_amount);

        }

        const paid =
            document.querySelector('#sum-paid-amount');

        if (paid) {

            paid.textContent =
                this.money(summary.paid_amount);

        }

        const debt =
            document.querySelector('#sum-debt-amount');

        if (debt) {

            debt.textContent =
                this.money(summary.debt_amount);

        }

    },


    /* =================================================
       TABLE
    ================================================= */

    renderTable() {

        const tbody =
            document.querySelector('#order-table-body');

        if (!tbody) return;

        if (!State.orders?.length) {

            tbody.innerHTML = `

                <tr>

                    <td
                        colspan="9"
                        class="text-center text-muted"
                    >

                        Không có dữ liệu

                    </td>

                </tr>

            `;

            return;

        }

        tbody.innerHTML =

            State.orders.map((order, index) => `

                <tr>

                    <td>
                        ${index + 1}
                    </td>

                    <td>
                        ${order.customer_name ?? ''}
                    </td>

                    <td>
                        ${this.money(order.total_amount)}
                    </td>

                    <td>
                        ${this.money(order.paid_amount)}
                    </td>

                    <td>
                        ${this.money(order.debt_amount)}
                    </td>

                    <!-- STATUS -->

                    <td>

                        <select

                            class="
                                form-select
                                form-select-sm
                                order-status
                                text-${
                                    this.getColor(
                                        State.options?.statuses,
                                        order.status
                                    )
                                }
                            "

                            data-id="${order.id}"

                        >

                            ${
                                this.renderOptions(
                                    State.options?.statuses || {},
                                    order.status
                                )
                            }

                        </select>

                    </td>

                    <!-- PAYMENT -->

                    <td>

                        <select

                            class="
                                form-select
                                form-select-sm
                                order-payment
                                text-${
                                    this.getColor(
                                        State.options?.payments,
                                        order.payment
                                    )
                                }
                            "

                            data-id="${order.id}"

                        >

                            ${
                                this.renderOptions(
                                    State.options?.payments || {},
                                    order.payment
                                )
                            }

                        </select>

                    </td>

                    <td>
                        ${order.created_at ?? ''}
                    </td>

                    <td>

                        <a

                            href="/admin/orders/edit/${order.id}"

                            class="
                                btn
                                btn-sm
                                btn-outline-secondary
                            "

                        >

                            Sửa

                        </a>

                        <button

                            type="button"

                            class="
                                btn
                                btn-sm
                                btn-outline-danger
                                btn-delete-order
                            "

                            data-id="${order.id}"

                        >

                            Xóa

                        </button>

                    </td>

                </tr>

            `).join('');

    },


    /* =================================================
       OPTIONS
    ================================================= */

    renderOptions(data = {}, selected) {

        return Object.entries(data)

            .map(([value, item]) => `

                <option

                    value="${value}"

                    ${value == selected
                        ? 'selected'
                        : ''
                    }

                >

                    ${item.label}

                </option>

            `)

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

        return Number(value || 0)
            .toLocaleString('vi-VN');

    }

};

export default Renderer;