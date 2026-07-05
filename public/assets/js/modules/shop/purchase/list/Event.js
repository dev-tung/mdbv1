import State from './State.js';
import Service from './Service.js';

const Event = {

    init() {

        this.bindFilters();
        this.bindTable();

    },

    bindFilters() {

        document.querySelector('#filter-date-from')
            ?.addEventListener('change', async e => {

                State.filter.date_from = e.target.value;
                State.filter.page = 1;

                await Service.loadPurchases();

            });

        document.querySelector('#filter-date-to')
            ?.addEventListener('change', async e => {

                State.filter.date_to = e.target.value;
                State.filter.page = 1;

                await Service.loadPurchases();

            });

        document.querySelector('#filter-supplier')
            ?.addEventListener('change', async e => {

                State.filter.supplier_id = e.target.value;
                State.filter.page = 1;

                await Service.loadPurchases();

            });

        document.querySelector('#filter-payment')
            ?.addEventListener('change', async e => {

                State.filter.payment = e.target.value;
                State.filter.page = 1;

                await Service.loadPurchases();

            });

    },

    bindTable() {

        document.querySelector('#purchase-table-body')
            ?.addEventListener('change', async e => {

                if (e.target.classList.contains('purchase-status')) {

                    await Service.updateStatus(
                        Number(e.target.dataset.id),
                        e.target.value
                    );

                }

                if (e.target.classList.contains('purchase-payment')) {

                    await Service.payment(
                        Number(e.target.dataset.id),
                        e.target.value
                    );

                }

            });

    }

};

export default Event;