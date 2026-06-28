import { Supplier } from "./supplier.js";
import { Product } from "./product.js";
import { Warehouse } from "./warehouse.js";
import { Submit } from "./submit.js";

document.addEventListener(
    "DOMContentLoaded",
    () => {

        Supplier.init();

        Product.init();

        Warehouse.load();

        Submit.init();
    }
);