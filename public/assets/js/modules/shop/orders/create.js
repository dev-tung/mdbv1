import {CustomerSearch} from "./customer.js";
import {ProductSearch} from "./product.js";
import {OrderSubmit} from "./submit.js";
import {Cart} from "./cart.js";


const OrderCreate = {

    init(){

        CustomerSearch.init();

        ProductSearch.init();

        OrderSubmit.init();

        Cart.render();

    }

};


document.addEventListener(
"DOMContentLoaded",
()=>OrderCreate.init()
);