import State from './State.js';
import Service from './Service.js';
import Renderer from './Renderer.js';


const Event = {


    bind() {

        this.customer();

        this.product();

        this.order();

        this.items();

        this.submit();

    },



    /* =================================================
       CUSTOMER
    ================================================= */

    customer() {


        const input =
            document.querySelector('#customer_search');


        const suggestions =
            document.querySelector('#customer_suggestions');



        input?.addEventListener(
            'input',
            async e => {


                State.customer.keyword =
                    e.target.value.trim();



                await Service.searchCustomers();



                Renderer.customerSuggestions();


            }
        );




        suggestions?.addEventListener(
            'click',
            e => {


                const button =
                    e.target.closest('.customer-item');



                if (!button) return;



                const customer =
                    State.customer.suggestions.find(

                        item =>
                            item.id == button.dataset.id

                    );



                if (!customer) return;



                Service.setCustomer(customer);



                input.value =
                    customer.name;



                State.customer.suggestions = [];



                Renderer.customerSuggestions();


            }
        );


    },






    /* =================================================
       PRODUCT
    ================================================= */

    product() {


        const input =
            document.querySelector('#product_search');



        const suggestions =
            document.querySelector('#product_suggestions');



        input?.addEventListener(
            'input',
            async e => {


                State.product.keyword =
                    e.target.value.trim();



                await Service.searchProducts();



                Renderer.productSuggestions();


            }
        );




        suggestions?.addEventListener(
            'click',
            e => {


                console.log('CLICK PRODUCT');

                const button =
                    e.target.closest('.product-item');


                console.log(button);



                if (!button) return;



                const product =
                    State.product.suggestions.find(

                        item =>
                            item.product_id == button.dataset.id

                    );



                if (!product) return;



                Service.addProduct(product);



                Service.calculate();



                Renderer.products();

                Renderer.summary();



                input.value = '';



                suggestions.classList.add('d-none');


            }
        );


    },






    /* =================================================
       ORDER
    ================================================= */

    order() {



        document.querySelector('#description')
            ?.addEventListener(
                'input',
                e => {


                    Service.setDescription(
                        e.target.value
                    );


                }
            );





        document.querySelector('#status')
            ?.addEventListener(
                'change',
                e => {


                    Service.setStatus(
                        e.target.value
                    );


                }
            );





        document.querySelector('#payment')
            ?.addEventListener(
                'change',
                e => {


                    Service.setPayment(
                        e.target.value
                    );



                    Renderer.payment();


                }
            );







        document.querySelector('#paid_amount')
            ?.addEventListener(
                'input',
                e => {


                    Service.setPaidAmount(
                        e.target.value
                    );



                    Renderer.summary();


                }
            );







        document.querySelector('#vat_rate')
            ?.addEventListener(
                'input',
                e => {


                    Service.setVatRate(
                        e.target.value
                    );



                    Service.calculate();



                    Renderer.products();

                    Renderer.summary();


                }
            );







        document.querySelector('#note')
            ?.addEventListener(
                'input',
                e => {


                    Service.setNote(
                        e.target.value
                    );


                }
            );


    },








    /* =================================================
       ITEMS
    ================================================= */

    items() {


        const table =
            document.querySelector('#selected_products');



        if (!table) return;




        table.addEventListener(
            'input',
            e => {


                const row =
                    e.target.closest('tr');



                if (!row) return;




                const index =
                    Number(
                        row.dataset.index
                    );



                const value =
                    e.target.value;




                const classList =
                    e.target.classList;




                if (
                    classList.contains('quantity')
                ) {


                    Service.setQuantity(
                        index,
                        value
                    );


                    Service.calculate();


                }





                else if (
                    classList.contains('selling-price')
                ) {


                    Service.setSellingPrice(
                        index,
                        value
                    );


                    Service.calculate();


                }

                else if (
                    classList.contains('is-gift')
                ) {

                    Service.setGift(
                        index,
                        e.target.checked
                    );

                    Renderer.products();

                    Renderer.summary();

                    return;

                }



                Renderer.productsUpdate(index);

                Renderer.summary();



            }
        );









        table.addEventListener(
            'click',
            e => {


                const button =
                    e.target.closest('.btn-remove');



                if (!button) return;




                const row =
                    button.closest('tr');



                if (!row) return;




                const index =
                    Number(
                        row.dataset.index
                    );




                Service.removeProduct(index);



                Service.calculate();



                Renderer.products();

                Renderer.summary();



            }
        );


    },








    /* =================================================
       SUBMIT
    ================================================= */

    submit() {


        document.querySelector('#order-form')
            ?.addEventListener(
                'submit',
                async e => {


                    e.preventDefault();




                    const response =
                        await Service.save();




                    alert(
                        response.message
                    );




                    if(response.success){


                        window.location.href =
                            response.redirect;


                    }


                }
            );


    }


};


export default Event;