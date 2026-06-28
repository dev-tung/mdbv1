import {OrderState} from "./state.js";
import {Formatter} from "../formatter.js";


export const Cart = {

    render(){

        const tbody=document.getElementById("selected_products");

        tbody.innerHTML="";


        OrderState.get()
        .forEach(p=>{

            tbody.innerHTML+=`
                <tr>
                    <td>${p.name}</td>
                    <td>${p.price}</td>
                </tr>
            `;

        });

        this.calc();

    },


    calc(){

        let total=0;


        OrderState.get()
        .forEach(p=>{

            total+=p.price*p.quantity;

        });


        document
        .getElementById("total_amount")
        .textContent=
        Formatter.money(total);

    }

};