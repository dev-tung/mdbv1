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

        const filters = [
            'date-from',
            'date-to',
            'supplier',
            'payment'
        ];


        filters.forEach(type => {

            document.querySelector(`#filter-${type}`)
                ?.addEventListener('change', async e => {


                    const key = type.replace('-', '_');


                    State.filter[key] = e.target.value;

                    State.filter.page = 1;


                    await this.reload();


                });

        });


    },


    /* =========================
       TABLE
    ========================= */

    bindTable() {

        const table =
            document.querySelector('#purchase-table-body');


        if (!table) return;



        table.addEventListener('change', async e => {


            const id =
                Number(e.target.dataset.id);


            if (!id) return;



            let response;



            if (
                e.target.classList.contains('purchase-status')
            ) {

                response =
                    await Service.updateStatus(
                        id,
                        e.target.value
                    );

            }


            if (
                e.target.classList.contains('purchase-payment')
            ) {

                response =
                    await Service.payment(
                        id,
                        e.target.value
                    );

            }


            if (response) {

                alert(response.message);

                await this.reload();

            }


        });



        table.onclick = async e => {


            const button =
                e.target.closest(
                    '.btn-delete-purchase'
                );


            if (!button) return;



            if (!confirm(
                'Bạn có chắc muốn xóa phiếu nhập này?'
            )) return;



            const response =
                await Service.deletePurchase(
                    Number(button.dataset.id)
                );


            alert(response.message);



            if (response.success) {

                await this.reload();

            }


        };


    },


    /* =========================
       RELOAD
    ========================= */

    async reload() {

        await Service.loadPurchases();

        Renderer.render();

    }


};


export default Event;