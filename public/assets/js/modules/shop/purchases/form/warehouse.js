import { Api } from "../../../../common/api.js";

export const Warehouse = {

    async init(apiUrl) {
        this.selectEl = document.getElementById("warehouse_id");
        if (!this.selectEl) return;

        await this.load(apiUrl);
    },

    async load(apiUrl) {
        try {
            const res = await Api.get(apiUrl);

            const warehouses = res?.data ?? [];

            this.render(warehouses);

        } catch (error) {
            console.error("Lỗi load warehouse:", error);
        }
    },

    render(warehouses) {
        this.selectEl.innerHTML = "";

        // option mặc định
        const defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = "-- Chọn kho nhập --";
        this.selectEl.appendChild(defaultOption);

        warehouses.forEach(wh => {
            const option = document.createElement("option");
            option.value = wh.id;
            option.textContent = `${wh.name} - ${wh.address ?? ""}`;

            this.selectEl.appendChild(option);
        });
    }
};