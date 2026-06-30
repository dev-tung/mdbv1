export const Binding = {

    api: null,
    options: null,

    // =========================
    // INIT
    // =========================
    init({ api, options }) {

        this.api = api || {};
        this.options = options || {};

        this.filter_suppliers();
        this.filter_payment();
        this.table();
    },

    // =========================
    // SUPPLIERS FILTER
    // =========================
    async filter_suppliers() {

        const el = document.getElementById('filter-supplier');
        if (!el || !this.api.suppliers) return;

        try {

            const res = await fetch(this.api.suppliers);
            const data = await res.json();

            const items = Array.isArray(data)
                ? data
                : (data.items || data.data || []);

            el.innerHTML = `
                <option value="">Nhà cung cấp</option>

                ${items.map(item => `
                    <option value="${item.id}">
                        ${item.name}
                    </option>
                `).join('')}
            `;

        } catch (e) {

            console.error('Load suppliers error:', e);

        }

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
    // TABLE INIT
    // =========================
    table() {

        this.load();

        [
            'filter-supplier',
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
                supplier_id: document.getElementById('filter-supplier')?.value || '',
                payment: document.getElementById('filter-payment')?.value || '',
                date_from: document.getElementById('filter-date-from')?.value || '',
                date_to: document.getElementById('filter-date-to')?.value || ''
            });

            const res = await fetch(`${this.api.list}?${query}`);
            const data = await res.json();

            this.render(data);

        } catch (e) {

            console.error('Load purchases error:', e);

        }

    },

    // =========================
    // RENDER TABLE
    // =========================
    render(data) {

        const items = Array.isArray(data)
            ? data
            : (data.items || data.data || []);

        const tbody = document.getElementById('purchase-table-body');

        if (!tbody) return;

        if (!items.length) {

            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-muted">
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

                <td>${item.supplier_name ?? ''}</td>

                <td>${item.warehouse_name ?? ''}</td>

                <td>${Number(item.total_amount ?? 0).toLocaleString()}</td>

                <td>
                    <select
                        class="form-select form-select-sm purchase-status"
                        data-id="${item.id}">

                        ${Object.entries(statuses).map(([value, status]) => `
                            <option
                                value="${value}"
                                ${item.status === value ? 'selected' : ''}>
                                ${status.label}
                            </option>
                        `).join('')}

                    </select>
                </td>

                <td>
                    <select
                        class="form-select form-select-sm purchase-payment"
                        data-id="${item.id}">

                        ${Object.entries(payments).map(([value, payment]) => `
                            <option
                                value="${value}"
                                ${item.payment === value ? 'selected' : ''}>
                                ${payment.label}
                            </option>
                        `).join('')}

                    </select>
                </td>

                <td>${item.created_at ?? ''}</td>

                <td>

                    <a
                        href="/admin/purchases/edit/${item.id}"
                        class="btn btn-sm btn-outline-secondary">
                        Sửa
                    </a>

                    <button
                        class="btn btn-sm btn-outline-secondary btn-delete"
                        data-id="${item.id}">
                        Xóa
                    </button>

                </td>

            </tr>

        `).join('');

        // Tổng tiền
        document.getElementById('total-amount').textContent =
            Number(data.total_amount ?? 0).toLocaleString();

        // Màu select
        this.bindSelectColors();

        // Pagination (nếu API có)
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

        // STATUS
        document.querySelectorAll('.purchase-status').forEach(select => {

            const update = () => {

                select.classList.remove(...classes);

                const status = this.options.statuses?.[select.value];

                let color = status?.color || 'secondary';

                if (color === 'default') {
                    color = 'secondary';
                }

                select.classList.add(`text-${color}`);
            };

            update();

            select.addEventListener('change', update);
        });

        // PAYMENT
        document.querySelectorAll('.purchase-payment').forEach(select => {

            const update = () => {

                select.classList.remove(...classes);

                const payment = this.options.payments?.[select.value];

                let color = payment?.color || 'secondary';

                if (color === 'default') {
                    color = 'secondary';
                }

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
                    <a
                        href="javascript:void(0)"
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