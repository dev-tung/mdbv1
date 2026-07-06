import State from './State.js';

const Renderer = {

    render() {

        this.renderSuppliers();
        this.renderPayments();
        this.renderSummary();
        this.renderTable();
        this.renderPagination();

    },

    renderSuppliers() {

        const select = document.querySelector('#filter-supplier');
        if (!select) return;

        select.innerHTML = `
            <option value="">Nhà cung cấp</option>
            ${State.suppliers.map(s => `
                <option
                    value="${s.id}"
                    ${State.filter.supplier_id == s.id ? 'selected' : ''}>
                    ${s.name}
                </option>
            `).join('')}`;
    },

    renderPayments() {
        const select = document.querySelector('#filter-payment');
        if (!select) return;

        select.innerHTML = `
            <option value="">Thanh toán</option>
            ${Object.entries(State.options.payments).map(([value, option]) => `
                <option
                    value="${value}"
                    ${State.filter.payment == value ? 'selected' : ''}>
                    ${option.label}
                </option>
            `).join('')}`;
    },

    renderSummary() {

        document.querySelector('#sum-total-amount').textContent =
            this.money(State.summary.total_amount);

        document.querySelector('#sum-paid-amount').textContent =
            this.money(State.summary.paid_amount);

        document.querySelector('#sum-debt-amount').textContent =
            this.money(State.summary.debt_amount);

    },

    renderTable() {
        const tbody = document.querySelector('#order-table-body');
        if (!tbody) return;

        if (!State.orders.length) {

            tbody.innerHTML = `
                <tr>
                    <td colspan="10" class="text-center text-muted">
                        Không có dữ liệu
                    </td>
                </tr>`;

            return;

        }

        tbody.innerHTML = State.orders.map((order, index) => `
            <tr>

                <td>${index + 1}</td>

                <td>${order.supplier_name ?? ''}</td>

                <td>${order.warehouse_name ?? ''}</td>

                <td>${this.money(order.total_amount)}</td>

                <td>${this.money(order.paid_amount)}</td>

                <td>${this.money(order.debt_amount)}</td>

                <td>
                    <select
                        class="form-select form-select-sm order-status text-${State.options.statuses[order.status]?.color} "
                        data-id="${order.id}">

                        ${Object.entries(State.options.statuses).map(([value, option]) => `
                            <option
                                value="${value}"
                                ${order.status === value ? 'selected' : ''}>
                                ${option.label}
                            </option>
                        `).join('')}

                    </select>
                </td>

                <td>
                    <select
                        class="form-select form-select-sm order-payment text-${State.options.payments[order.payment]?.color}"
                        data-id="${order.id}">

                        ${Object.entries(State.options.payments).map(([value, option]) => `
                            <option
                                value="${value}"
                                ${order.payment === value ? 'selected' : ''}>
                                ${option.label}
                            </option>
                        `).join('')}

                    </select>
                </td>

                <td>${order.created_at ?? ''}</td>

                <td>
                    <a
                        href="/admin/orders/edit/${order.id}"
                        class="btn btn-sm btn-outline-secondary">
                        Sửa
                    </a>
                    <a
                        href="/admin/orders/delete/${order.id}"
                        class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                        Xóa
                    </a>
                </td>

            </tr>
        `).join('');

    },

    renderPagination() {

        // để sau
    },

    money(value) {

        return Number(value || 0).toLocaleString('vi-VN');

    }

};

export default Renderer;