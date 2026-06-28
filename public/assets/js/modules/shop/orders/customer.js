import { Api } from '../../../helpers/api.js';


export const CustomerSearch = {

    init(){

        const input=document.getElementById("customer_search");
        const id=document.getElementById("customer_id");
        const box=document.getElementById("customer_suggestions");


        input.addEventListener("input",async()=>{

            const keyword=input.value.trim();

            if(!keyword)return;


            const json=await Api.get(
                `/api/customers?keyword=${keyword}`
            );


            box.innerHTML="";


            (json.data||[]).forEach(c=>{

                const btn=document.createElement("button");

                btn.textContent=c.name;


                btn.onclick=()=>{

                    input.value=c.name;
                    id.value=c.id;

                    box.innerHTML="";

                };


                box.appendChild(btn);

            });


        });

    }

};