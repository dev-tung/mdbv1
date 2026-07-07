import Http from '/assets/js/common/Http.js';


const Api = {


    /* =================================================
       ORDER
    ================================================= */


    async showOrder(id) {


        return await Http.get(

            `/api/orders/show/${id}`

        );


    },



    async createOrder(data) {


        return await Http.post(

            '/api/orders',

            data

        );


    },



    async updateOrder(id, data) {


        return await Http.post(

            `/api/orders/update/${id}`,

            data

        );


    },



    async deleteOrder(id) {


        return await Http.post(

            `/api/orders/delete/${id}`

        );


    },



    /* =================================================
       PRODUCT
    ================================================= */


    async searchProducts(keyword = '') {


        return await Http.get(

            '/api/inventories/stock',

            {

                keyword

            }

        );


    },



    /* =================================================
       CUSTOMER
    ================================================= */


    async searchCustomers(keyword = '') {


        return await Http.get(

            '/api/customers',

            {

                keyword

            }

        );


    }


};


export default Api;