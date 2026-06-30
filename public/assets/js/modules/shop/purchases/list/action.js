export const Action = {

    api: null,

    init({ api }) {
        this.api = api;

        this.bindStatus();
        this.bindPayment();
        this.bindDelete();
    },

    bindStatus() {
        document.addEventListener('change', async (e) => {

            const el = e.target.closest('.status-select');
            if (!el) return;

            await fetch(`${this.api.status}/${el.dataset.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: el.value
                })
            });
        });
    },

    bindPayment() {
        document.addEventListener('change', async (e) => {

            const el = e.target.closest('.payment-select');
            if (!el) return;

            await fetch(`${this.api.payment}/${el.dataset.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    payment: el.value
                })
            });
        });
    },

    bindDelete() {
        document.addEventListener('click', async (e) => {

            const btn = e.target.closest('.btn-delete');
            if (!btn) return;

            if (!confirm('Delete item?')) return;

            await fetch(`${this.api.delete}/${btn.dataset.id}`, {
                method: 'DELETE'
            });
        });
    }
};