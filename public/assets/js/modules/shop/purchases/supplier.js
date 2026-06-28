import { Api } from "../../../helpers/api.js";

export const Supplier = {

    init() {

        const input =
            document.getElementById("supplier_search");

        const hidden =
            document.getElementById("supplier_id");

        const box =
            document.getElementById("supplier_suggestions");

        input.addEventListener("input", async () => {

            const keyword = input.value.trim();

            box.innerHTML = "";
            hidden.value = "";

            if (!keyword) {

                box.classList.add("d-none");

                return;
            }

            const json = await Api.get(
                `/api/suppliers?keyword=${encodeURIComponent(keyword)}`
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

                    input.value = item.name;

                    hidden.value = item.id;

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