export const Binding = {

    api: null,
    options: null,

    // =========================
    // INIT
    // =========================
    init({ api, options }) {

        this.api = api || {};
        this.options = options || {};

        requestAnimationFrame(() => {
            this.customerSearch();
            this.filter_payment();
            this.table();
        });
    },

    // =========================
    // CUSTOMER SEARCH
    // =========================
    customerSearch() {

        const input = document.getElementById('filter-customer-search');

        if (!input) return;

        let timer = null;

        input.addEventListener('input', () => {

            clearTimeout(timer);

            timer = setTimeout(() => {
                this.load(1);
            }, 300);

        });
    },

    // =========================
    // PAYMENT FILTER
    // =========================
    filter_payment() {

        const el = document.getElementById('filter-payment');
        if (!el) return;

        const payments = this.options.payments || {};

        el.innerHTML = `
            <option value="">Thanh toán</option>
            ${Object.entries(payments).map(([key, payment]) => `
                <option value="${key}">
                    ${payment.label}
                </option>
            `).join('')}
        `;
    },

    // =========================
    // SUMMARY
    // =========================
    sum(items = []) {

        const totalAmount = items.reduce(
            (sum, item) => sum + Number(item.total_amount || 0),
            0
        );

        const paidAmount = items.reduce(
            (sum, item) => sum + Number(item.paid_amount || 0),
            0
        );

        const debtAmount = items.reduce(
            (sum, item) => sum + Number(item.debt_amount || 0),
            0
        );

        const set = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        };

        set('sum-total-amount', totalAmount.toLocaleString('vi-VN'));
        set('sum-paid-amount', paidAmount.toLocaleString('vi-VN'));
        set('sum-debt-amount', debtAmount.toLocaleString('vi-VN'));
    },

    // =========================
    // TABLE INIT
    // =========================
    table() {

        this.load();

        [
            'filter-payment',
            'filter-date-from',
            'filter-date-to'
        ].forEach(id => {

            const el = document.getElementById(id);
            if (!el) return;

            el.addEventListener('change', () => {
                this.load(1);
            });
        });
    },

    // =========================
    // LOAD
    // =========================
    async load(page = 1) {

        try {

            const query = new URLSearchParams({
                page,
                keyword: document.getElementById('filter-customer-search')?.value || '',
                payment: document.getElementById('filter-payment')?.value || '',
                date_from: document.getElementById('filter-date-from')?.value || '',
                date_to: document.getElementById('filter-date-to')?.value || ''
            });

            const res = await fetch(`${this.api.list}?${query}`);
            const data = await res.json();

            this.render(data);

        } catch (e) {
            console.error('Load orders error:', e);
        }
    },

    // =========================
    // RENDER TABLE
    // =========================
    render(data) {

        const items = Array.isArray(data)
            ? data
            : (data.items || data.data || []);

        this.sum(items);

        const tbody = document.getElementById('order-table-body');
        if (!tbody) return;

        if (!items.length) {

            tbody.innerHTML = `
                <tr>
                    <td colspan="10" class="text-center text-muted">
                        Không có dữ liệu
                    </td>
                </tr>
            `;
            return;
        }

        const statuses = this.options.statuses || {};
        const payments = this.options.payments || {};

        tbody.innerHTML = items.map(item => `

            <tr>

                <td>${item.id}</td>
                <td>${item.customer_name ?? ''}</td>

                <td>${Number(item.total_amount ?? 0).toLocaleString()}</td>
                <td>${Number(item.paid_amount ?? 0).toLocaleString()}</td>
                <td>${Number(item.debt_amount ?? 0).toLocaleString()}</td>

                <td>
                    <select class="form-select form-select-sm order-status"
                        data-id="${item.id}">

                        ${Object.entries(statuses).map(([value, status]) => `
                            <option value="${value}"
                                ${item.status === value ? 'selected' : ''}>
                                ${status.label}
                            </option>
                        `).join('')}

                    </select>
                </td>

                <td>
                    <select class="form-select form-select-sm order-payment"
                        data-id="${item.id}">

                        ${Object.entries(payments).map(([value, payment]) => `
                            <option value="${value}"
                                ${item.payment === value ? 'selected' : ''}>
                                ${payment.label}
                            </option>
                        `).join('')}

                    </select>
                </td>

                <td>${item.created_at ?? ''}</td>

                <td>
                    <a href="/admin/orders/edit/${item.id}"
                        class="btn btn-sm btn-outline-secondary">
                        Sửa
                    </a>

                    <button class="btn btn-sm btn-outline-secondary btn-delete"
                        data-id="${item.id}">
                        Xóa
                    </button>
                </td>

            </tr>

        `).join('');

        const totalEl = document.getElementById('total-amount');
        if (totalEl) {
            totalEl.textContent = Number(data.total_amount ?? 0).toLocaleString();
        }

        this.bindSelectColors();
        this.renderPagination(data);
    },

    // =========================
    // SELECT COLOR
    // =========================
    bindSelectColors() {

        const classes = [
            'text-danger',
            'text-success',
            'text-warning',
            'text-primary',
            'text-secondary',
            'text-info'
        ];

        document.querySelectorAll('.order-status').forEach(select => {

            const update = () => {

                select.classList.remove(...classes);

                const status = this.options.statuses?.[select.value];
                let color = status?.color || 'secondary';

                if (color === 'default') color = 'secondary';

                select.classList.add(`text-${color}`);
            };

            update();
            select.addEventListener('change', update);
        });

        document.querySelectorAll('.order-payment').forEach(select => {

            const update = () => {

                select.classList.remove(...classes);

                const payment = this.options.payments?.[select.value];
                let color = payment?.color || 'secondary';

                if (color === 'default') color = 'secondary';

                select.classList.add(`text-${color}`);
            };

            update();
            select.addEventListener('change', update);
        });
    },

    // =========================
    // PAGINATION
    // =========================
    renderPagination(data) {

        const container = document.getElementById('pagination-pages');
        if (!container) return;

        const current = Number(data.current_page || 1);
        const last = Number(data.last_page || 1);

        let html = '';

        for (let i = 1; i <= last; i++) {

            html += `
                <li class="page-item ${i === current ? 'active' : ''}">
                    <a href="javascript:void(0)"
                        class="page-link text-secondary ${i === current ? 'bg-light border-secondary' : ''}"
                        data-page="${i}">
                        ${i}
                    </a>
                </li>
            `;
        }

        container.innerHTML = html;

        container.querySelectorAll('[data-page]').forEach(link => {
            link.addEventListener('click', () => {
                this.load(link.dataset.page);
            });
        });
    }
};