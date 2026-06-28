import { Api } from '../../../helpers/api.js';
import {OrderState} from "./state.js";
import {Cart} from "./cart.js";


export const ProductSearch = {

    init(){

        const input=document.getElementById("product_search");
        const box=document.getElementById("product_suggestions");


        input.addEventListener("input",async()=>{

            const keyword=input.value.trim();


            const json=await Api.get(
                `/api/inventories/stock?keyword=${keyword}`
            );


            box.innerHTML="";


            (json.data||[]).forEach(p=>{

                const btn=document.createElement("button");

                btn.textContent=p.name;


                btn.onclick=()=>{

                    OrderState.add(p);

                    Cart.render();

                };


                box.appendChild(btn);

            });

        });

    }

};