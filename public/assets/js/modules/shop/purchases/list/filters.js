export const Filters = {

    init(url, config) {
        this.url = url;
        this.config = config;

        this.loadSuppliers();
        this.renderPaymentFilter();
    },

    async loadSuppliers() {

        const res = await fetch(this.url);
        const json = await res.json();

        const el = document.getElementById('filter-supplier');
        if (!el) return;

        el.innerHTML = `<option value="">Nhà cung cấp</option>`;

        json.data.forEach(s => {
            el.innerHTML += `
                <option value="${s.id}">
                    ${s.name}
                </option>
            `;
        });
    },

    renderPaymentFilter() {

        const el = document.getElementById('filter-payment');
        if (!el) return;

        const payments = this.config.payments;

        el.innerHTML = `<option value="">Thanh toán</option>`;

        Object.keys(payments).forEach(k => {
            el.innerHTML += `
                <option value="${k}">
                    ${payments[k].label}
                </option>
            `;
        });
    }
};