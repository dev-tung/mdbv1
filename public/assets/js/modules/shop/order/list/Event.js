import State from './State.js';
import Service from './Service.js';
import Renderer from './Renderer.js';

const Event = {
    init() {
        this.bindFilters();

        this.bindTable();
    },

    /* =========================
       FILTER
    ========================= */

    bindFilters() {
        [
            ['date-from', 'change'],
            ['date-to', 'change'],
            ['payment', 'change'],
            ['status', 'change'],
            ['customer', 'keyup'],
        ].forEach(([type, event]) => {
            document.querySelector(`#filter-${type}`)?.addEventListener(event, async e => {
                State.filter[type.replace('-', '_')] = e.target.value.trim();

                State.filter.page = 1;

                await this.reload();
            });
        });
    },

    /* =========================
       TABLE
    ========================= */

    bindTable() {
        const table = document.querySelector('#order-table-body');

        if (!table) return;

        /*
            CHANGE
        */

        table.addEventListener('change', async e => {
            const id = Number(e.target.dataset.id);

            if (!id) return;

            let response = null;

            if (e.target.classList.contains('order-status')) {
                response = await Service.status(id, e.target.value);
            }

            if (e.target.classList.contains('order-payment')) {
                response = await Service.payment(id, e.target.value);
            }

            if (response) {
                alert(response.message);

                await this.reload();
            }
        });

        /*
            CLICK
        */

        table.addEventListener('click', async e => {
            /*
                    DELETE
                */

            const button = e.target.closest('.btn-delete-order');

            if (button) {
                if (!confirm('Bạn có chắc muốn xóa đơn hàng này?')) return;

                const response = await Service.deleteOrder(Number(button.dataset.id));

                alert(response.message);

                if (response.success) {
                    await this.reload();
                }

                return;
            }
        });
    },

    /* =========================
       RELOAD
    ========================= */

    async reload() {
        await Service.loadOrders();

        Renderer.render();
    },
};

export default Event;
