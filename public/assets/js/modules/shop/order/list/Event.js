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

            'customer',

            'payment',

            'status'

        ];



        filters.forEach(type => {


            document.querySelector(`#filter-${type}`)
                ?.addEventListener(
                    'change',
                    async e => {


                        const key =
                            type.replace('-', '_');



                        State.filter[key] =
                            e.target.value;



                        State.filter.page = 1;



                        await this.reload();


                    }
                );


        });


    },



    /* =========================
       TABLE
    ========================= */

    bindTable() {


        const table =
            document.querySelector(
                '#order-table-body'
            );


        if (!table) return;



        /*
            CHANGE
        */

        table.addEventListener(
            'change',
            async e => {


                const id =
                    Number(
                        e.target.dataset.id
                    );



                if (!id) return;



                let response = null;



                if (
                    e.target.classList.contains(
                        'order-status'
                    )
                ) {


                    response =
                        await Service.updateStatus(
                            id,
                            e.target.value
                        );


                }




                if (
                    e.target.classList.contains(
                        'order-payment'
                    )
                ) {


                    response =
                        await Service.payment(
                            id,
                            e.target.value
                        );


                }



                if (response) {


                    alert(
                        response.message
                    );



                    await this.reload();


                }


            }
        );





        /*
            CLICK
        */

        table.addEventListener(
            'click',
            async e => {



                /*
                    DELETE
                */

                const button =
                    e.target.closest(
                        '.btn-delete-order'
                    );



                if (button) {



                    if (
                        !confirm(
                            'Bạn có chắc muốn xóa đơn hàng này?'
                        )
                    ) return;



                    const response =
                        await Service.deleteOrder(
                            Number(
                                button.dataset.id
                            )
                        );



                    alert(
                        response.message
                    );



                    if(response.success){


                        await this.reload();


                    }


                    return;

                }




            }
        );


    },




    /* =========================
       RELOAD
    ========================= */

    async reload() {


        await Service.loadOrders();


        Renderer.render();


    }


};


export default Event;