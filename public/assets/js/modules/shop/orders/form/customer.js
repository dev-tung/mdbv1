import { Api } from "../../../../common/api.js";

export const Customer = {

    async init(apiUrl) {
        this.input = document.getElementById("customer_search");
        this.hidden = document.getElementById("customer_id");
        this.box = document.getElementById("customer_suggestions");

        if (!this.input || !this.hidden || !this.box) {
            console.error("Customer DOM not found");
            return;
        }

        await this.load(apiUrl);
        this.bind();
    },

    async load(apiUrl) {
        try {
            const res = await Api.get(apiUrl);
            // API của bạn: { data: [...] }
            this.customers = res?.data ?? [];

        } catch (error) {
            console.error("Load customer error:", error);
            this.customers = [];
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

            const filtered = this.customers.filter(s =>
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
            item.textContent = s.name + ' - ' + s.group_name;

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