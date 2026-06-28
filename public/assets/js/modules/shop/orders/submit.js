import { Api } from '../../../helpers/api.js';
import {OrderState} from "./state.js";


export const OrderSubmit = {

    init(){

        document
        .getElementById("order-create-form")
        .addEventListener("submit",async e=>{

            e.preventDefault();


            await Api.post(
                "/api/orders",
                {
                    customer_id:
                    document.getElementById("customer_id").value,

                    products:
                    OrderState.get()
                }
            );


            location.href="/admin/orders";

        });

    }

};