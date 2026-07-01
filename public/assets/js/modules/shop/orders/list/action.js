import { Binding } from './binding.js';

export const Action = {

    api: null,

    init({ api }) {
        this.api = api;
        this.bindEvents();
    },

    bindEvents() {

        document.addEventListener('change', async (e) => {

            const statusSelect = e.target.closest('.order-status');
            const paymentSelect = e.target.closest('.order-payment');

            // =========================
            // STATUS
            // =========================
            if (statusSelect) {

                try {

                    await fetch(this.api.status, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            id: statusSelect.dataset.id,
                            status: statusSelect.value
                        })
                    });

                    Binding.bindSelectColors();

                } catch (err) {
                    console.error('Update status error:', err);
                }
            }

            // =========================
            // PAYMENT
            // =========================
            if (paymentSelect) {

                try {

                    await fetch(this.api.payment, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            id: paymentSelect.dataset.id,
                            payment: paymentSelect.value
                        })
                    });

                    //không reload page nữa
                    Binding.load();

                } catch (err) {
                    console.error('Update payment error:', err);
                }
            }

        });

        document.addEventListener('click', async (e) => {

            const btn = e.target.closest('.btn-delete');
            if (!btn) return;

            if (!confirm('Bạn có chắc muốn xóa?')) return;

            try {

                await fetch(`${this.api.delete}/${btn.dataset.id}`, {
                    method: 'POST'
                });

                btn.closest('tr')?.remove();

            } catch (err) {
                console.error('Delete error:', err);
            }

        });
    }
};