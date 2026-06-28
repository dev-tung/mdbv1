export async function get(url){
    return await fetch(url).then(r=>r.json());
}

export async function post(url,data){

    return await fetch(url,{
        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },
        body:JSON.stringify(data)
    }).then(r=>r.json());

}