import { Api } from '../../../helpers/api.js';

export const CustomerSearch = {

    init() {

        const input = document.getElementById("customer_search");
        const customerId = document.getElementById("customer_id");
        const box = document.getElementById("customer_suggestions");

        if (!input || !customerId || !box) return;

        input.addEventListener("input", async () => {

            const keyword = input.value.trim();

            // Không có keyword
            if (!keyword) {
                customerId.value = "";
                box.innerHTML = "";
                box.classList.add("d-none");
                return;
            }

            try {

                const json = await Api.get(
                    `/api/customers?keyword=${encodeURIComponent(keyword)}`
                );

                const customers = json.data || [];

                box.innerHTML = "";

                // Không có kết quả
                if (!customers.length) {
                    box.classList.add("d-none");
                    return;
                }

                customers.forEach(customer => {

                    const btn = document.createElement("button");

                    btn.type = "button";
                    btn.className =
                        "list-group-item list-group-item-action";

                    btn.textContent = customer.name;

                    btn.addEventListener("click", () => {

                        input.value = customer.name;
                        customerId.value = customer.id;

                        box.innerHTML = "";
                        box.classList.add("d-none");

                    });

                    box.appendChild(btn);

                });

                box.classList.remove("d-none");

            } catch (error) {

                console.error(error);

                box.innerHTML = "";
                box.classList.add("d-none");

            }

        });

        // Click ra ngoài thì ẩn dropdown
        document.addEventListener("click", (e) => {

            if (
                !input.contains(e.target) &&
                !box.contains(e.target)
            ) {
                box.classList.add("d-none");
            }

        });

    }

};