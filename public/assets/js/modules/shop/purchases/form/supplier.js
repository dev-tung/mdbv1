import { Api } from "../../../../common/api.js";

export const Supplier = {

    async init(apiUrl) {
        this.input = document.getElementById("supplier_search");
        this.hidden = document.getElementById("supplier_id");
        this.box = document.getElementById("supplier_suggestions");

        if (!this.input || !this.hidden || !this.box) {
            console.error("Supplier DOM not found");
            return;
        }

        await this.load(apiUrl);
        this.bind();
    },

    async load(apiUrl) {
        try {
            const res = await Api.get(apiUrl);
            // API của bạn: { data: [...] }
            this.suppliers = res?.data ?? [];

        } catch (error) {
            console.error("Load supplier error:", error);
            this.suppliers = [];
        }
    },

    bind() {
        this.input.addEventListener("input", () => {

            const keyword = this.input.value.trim().toLowerCase();

            if (!keyword) {
                this.box.classList.add("d-none");
                this.box.innerHTML = "";
                return;
            }

            const filtered = this.suppliers.filter(s =>
                (s.name || "").toLowerCase().includes(keyword)
            );

            this.render(filtered);
        });

        // click outside để đóng dropdown
        document.addEventListener("click", (e) => {
            if (!this.box.contains(e.target) && e.target !== this.input) {
                this.box.classList.add("d-none");
            }
        });
    },

    render(list) {

        this.box.innerHTML = "";

        if (!list.length) {
            this.box.classList.add("d-none");
            return;
        }

        list.forEach(s => {
            const item = document.createElement("button");
            item.type = "button";
            item.className = "list-group-item list-group-item-action";
            item.textContent = s.name;

            item.onclick = () => {
                this.input.value = s.name;
                this.hidden.value = s.id;

                this.box.classList.add("d-none");
            };

            this.box.appendChild(item);
        });

        this.box.classList.remove("d-none");
    }
};