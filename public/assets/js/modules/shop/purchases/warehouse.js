import { Api } from "../../../helpers/api.js";

export const Warehouse = {

    async load() {

        const select =
            document.getElementById("warehouse_id");

        const json =
            await Api.get("/api/warehouses");

        const data = json.data || [];

        select.innerHTML = "";

        data.forEach(item => {

            const option =
                document.createElement("option");

            option.value = item.id;

            option.textContent = item.name;

            select.appendChild(option);
        });
    }
};