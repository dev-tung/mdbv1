export const Binding = {

    api: null,
    options: null,

    // =========================
    // INIT
    // =========================
    init({ api, options }) {

        this.api = api || {};
        this.options = options || {};

        console.log('Binding init:', this.api, this.options);

        this.filter_suppliers();
        this.filter_status();
        this.table();
    },

    // =========================
    // SUPPLIERS FILTER
    // =========================
    filter_suppliers() {

        const url = this.api?.suppliers;
        const el = document.getElementById('filter-supplier');

        if (!url || !el) {
            console.warn('filter_suppliers skipped (missing url or element)');
            return;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {

                const items = Array.isArray(data)
                    ? data
                    : (data.items || data.data || []);

                el.innerHTML = items.map(item => `
                    <option value="${item.id}">${item.name}</option>
                `).join('');
            })
            .catch(err => {
                console.error('filter_suppliers error:', err);
            });
    },

    // =========================
    // STATUS FILTER
    // =========================
    filter_status() {

        const el = document.getElementById('filter-status');

        if (!el) {
            console.warn('filter_status skipped (element not found)');
            return;
        }

        const statuses = this.options?.statuses || {};

        el.innerHTML = Object.entries(statuses).map(([key, label]) => `
            <option value="${key}">${label}</option>
        `).join('');
    },

    // =========================
    // TABLE INIT
    // =========================
    table() {

        if (!this.api?.list) {
            console.error('Binding.table: api.list is missing');
            return;
        }

        this.load();

        const els = [
            'filter-supplier',
            'filter-status',
            'filter-payment'
        ];

        els.forEach(id => {

            const el = document.getElementById(id);

            if (!el) {
                console.warn(`Missing filter element: ${id}`);
                return;
            }

            el.addEventListener('change', () => {
                this.load(1);
            });
        });
    },

    // =========================
    // LOAD DATA
    // =========================
    async load(page = 1) {

        try {

            const supplier = document.getElementById('filter-supplier')?.value || '';
            const status = document.getElementById('filter-status')?.value || '';
            const payment = document.getElementById('filter-payment')?.value || '';

            const query = new URLSearchParams({
                page,
                supplier_id: supplier,
                status,
                payment
            });

            const res = await fetch(`${this.api.list}?${query}`);

            const data = await res.json();

            this.render(data);

        } catch (err) {
            console.error('Binding.load error:', err);
        }
    },

    // =========================
    // RENDER TABLE
    // =========================
    render(data) {

        const items = Array.isArray(data)
            ? data
            : (data.items || data.data || []);

        const el = document.getElementById('table-body');

        if (!el) {
            console.warn('table-body not found');
            return;
        }

        if (!items.length) {
            el.innerHTML = `
                <tr>
                    <td colspan="3" style="text-align:center;">No data</td>
                </tr>
            `;
            return;
        }

        el.innerHTML = items.map(item => `
            <tr>
                <td>${item.id ?? ''}</td>
                <td>${item.supplier_name ?? ''}</td>
                <td>${item.total ?? 0}</td>
            </tr>
        `).join('');
    }
};