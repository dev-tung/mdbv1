export function autocomplete({input,box,search,onSelect}){

    input.addEventListener("input",async function(){

        const keyword=this.value.trim();

        box.innerHTML="";

        if(!keyword){
            box.classList.add("d-none");
            return;
        }

        const data=await search(keyword);

        if(!data.length){
            box.classList.add("d-none");
            return;
        }

        data.forEach(item=>{

            const btn=document.createElement("button");

            btn.type="button";
            btn.className="list-group-item list-group-item-action";
            btn.textContent=item.name;

            btn.onclick=()=>{

                onSelect(item);

                box.classList.add("d-none");

            };

            box.appendChild(btn);

        });

        box.classList.remove("d-none");

    });

}