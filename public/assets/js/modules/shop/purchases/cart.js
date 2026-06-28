import { PurchaseState } from "./state.js";
import { Formatter } from "../../../helpers/formatter.js";
export const Cart = {

    render() {

        const tbody =
            document.getElementById("selected_products");

        const totalEl =
            document.getElementById("total_amount");

        tbody.innerHTML = "";

        PurchaseState.get().forEach(product => {

            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td>${product.name}</td>

                <td>
                    <input
                        type="number"
                        min="1"
                        value="${product.quantity}"
                        class="form-control form-control-sm qty"
                        data-id="${product.id}">
                </td>

                <td>
                    <input
                        type="number"
                        min="0"
                        value="${product.price}"
                        class="form-control form-control-sm price"
                        data-id="${product.id}">
                </td>

                <td
                    class="item-total"
                    data-id="${product.id}">
                </td>

                <td>
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger remove"
                        data-id="${product.id}">
                        Xóa
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        });

        this.bind();
        this.calculate();

        totalEl.textContent =
            Formatter.money(this.total());
    },

    bind() {

        document.querySelectorAll(".qty")
        .forEach(input => {

            input.oninput = () => {

                PurchaseState.updateQuantity(
                    input.dataset.id,
                    +input.value || 1
                );

                this.calculate();
            };
        });

        document.querySelectorAll(".price")
        .forEach(input => {

            input.oninput = () => {

                PurchaseState.updatePrice(
                    input.dataset.id,
                    +input.value || 0
                );

                this.calculate();
            };
        });

        document.querySelectorAll(".remove")
        .forEach(button => {

            button.onclick = () => {

                PurchaseState.remove(button.dataset.id);

                this.render();
            };
        });
    },

    calculate() {

        PurchaseState.get().forEach(product => {

            const total =
                product.quantity * product.price;

            const el = document.querySelector(
                `.item-total[data-id="${product.id}"]`
            );

            if (el) {
                el.textContent =
                    Formatter.money(total);
            }
        });

        document.getElementById("total_amount")
        .textContent =
            Formatter.money(this.total());
    },

    total() {

        return PurchaseState.get()
        .reduce((sum, product) => {

            return sum +
                (product.quantity * product.price);

        }, 0);
    }
};