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
        const tbody = document.querySelector('#purchase-table-body');
        if (!tbody) return;

        if (!State.purchases.length) {

            tbody.innerHTML = `
                <tr>
                    <td colspan="10" class="text-center text-muted">
                        Không có dữ liệu
                    </td>
                </tr>`;

            return;

        }

        tbody.innerHTML = State.purchases.map((purchase, index) => `
            <tr>

                <td>${index + 1}</td>

                <td>${purchase.supplier_name ?? ''}</td>

                <td>${purchase.warehouse_name ?? ''}</td>

                <td>${this.money(purchase.total_amount)}</td>

                <td>${this.money(purchase.paid_amount)}</td>

                <td>${this.money(purchase.debt_amount)}</td>

                <td>
                    <select
                        class="form-select form-select-sm purchase-status text-${State.options.statuses[purchase.status]?.color} "
                        data-id="${purchase.id}">

                        ${Object.entries(State.options.statuses).map(([value, option]) => `
                            <option
                                value="${value}"
                                ${purchase.status === value ? 'selected' : ''}>
                                ${option.label}
                            </option>
                        `).join('')}

                    </select>
                </td>

                <td>
                    <select
                        class="form-select form-select-sm purchase-payment text-${State.options.payments[purchase.payment]?.color}"
                        data-id="${purchase.id}">

                        ${Object.entries(State.options.payments).map(([value, option]) => `
                            <option
                                value="${value}"
                                ${purchase.payment === value ? 'selected' : ''}>
                                ${option.label}
                            </option>
                        `).join('')}

                    </select>
                </td>

                <td>${purchase.created_at ?? ''}</td>

                <td>
                    <a
                        href="/admin/purchases/edit/${purchase.id}"
                        class="btn btn-sm btn-outline-secondary">
                        Sửa
                    </a>
                    <a
                        href="/admin/purchases/delete/${purchase.id}"
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