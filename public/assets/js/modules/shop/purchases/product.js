import { Api } from "../../../helpers/api.js";
import { PurchaseState } from "./state.js";
import { Cart } from "./cart.js";

export const Product = {

    init() {

        const input =
            document.getElementById("product_search");

        const box =
            document.getElementById("product_suggestions");

        input.addEventListener("input", async () => {

            const keyword = input.value.trim();

            box.innerHTML = "";

            if (!keyword) {

                box.classList.add("d-none");

                return;
            }

            const json = await Api.get(
                `/api/products?keyword=${encodeURIComponent(keyword)}`
            );

            const data = json.data || [];

            data.forEach(item => {

                const button =
                    document.createElement("button");

                button.type = "button";

                button.className =
                    "list-group-item list-group-item-action";

                button.textContent = item.name;

                button.onclick = () => {

                    PurchaseState.add(item);

                    Cart.render();

                    input.value = "";

                    box.classList.add("d-none");
                };

                box.appendChild(button);
            });

            box.classList.toggle(
                "d-none",
                !data.length
            );
        });
    }
};