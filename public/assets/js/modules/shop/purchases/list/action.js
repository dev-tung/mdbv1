export const Action = {

    api: null,

    init({ api }) {
        this.api = api;

        this.update_status();
        this.update_payment();
        this.delete();
    },

    // =========================
    // UPDATE STATUS
    // =========================
    update_status() {

        document.addEventListener('change', async (e) => {

            const select = e.target.closest('.purchase-status');
            if (!select) return;

            try {

                await fetch(this.api.status, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: select.dataset.id,
                        status: select.value
                    })
                });

            } catch (error) {

                console.error('Update status error:', error);

            }

        });

    },

    // =========================
    // UPDATE PAYMENT
    // =========================
    update_payment() {

        document.addEventListener('change', async (e) => {

            const select = e.target.closest('.purchase-payment');
            if (!select) return;

            try {

                await fetch(this.api.payment, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: select.dataset.id,
                        payment: select.value
                    })
                });

            } catch (error) {

                console.error('Update payment error:', error);

            }

        });

    },

    // =========================
    // DELETE
    // =========================
    delete() {

        document.addEventListener('click', async (e) => {

            const btn = e.target.closest('.btn-delete');
            if (!btn) return;

            if (!confirm('Bạn có chắc muốn xóa?')) return;

            try {

                await fetch(`${this.api.delete}/${btn.dataset.id}`, {
                    method: 'DELETE'
                });

                btn.closest('tr')?.remove();

            } catch (error) {

                console.error('Delete error:', error);

            }

        });

    }

};