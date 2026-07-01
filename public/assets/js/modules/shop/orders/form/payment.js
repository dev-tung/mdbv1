import { Product } from "./product.js";

export const Payment = {

    init() {

        this.paymentEl = document.getElementById("payment");
        this.paidEl = document.getElementById("paid_amount");
        this.paidWrapper = document.getElementById("paid_amount_wrapper");

        this.totalView = document.getElementById("total_amount");
        this.paidView = document.getElementById("paid_amount_view");
        this.debtView = document.getElementById("debt_amount_view");

        this.bindEvents();

        // sync khi product thay đổi
        window.addEventListener("order:update", () => {
            this.render();
        });

        this.render();
    },

    bindEvents() {

        this.paymentEl?.addEventListener("change", () => {
            this.togglePaidInput();
            this.render();
        });

        this.paidEl?.addEventListener("input", () => {
            this.render();
        });

        this.togglePaidInput();
    },

    togglePaidInput() {

        const type = this.paymentEl.value;

        if (type === "partial") {
            this.paidWrapper.classList.remove("d-none");
        } else {
            this.paidWrapper.classList.add("d-none");
            this.paidEl.value = 0;
        }
    },

    isPartial() {
        return this.paymentEl?.value === "partial";
    },

    getTotal() {
        return Product.getTotal();
    },

    getPaid() {
        return Number(this.paidEl?.value || 0);
    },

    getDebt() {
        return Math.max(this.getTotal() - this.getPaid(), 0);
    },

    render() {

        const total = this.getTotal();

        const paid = this.getPaid();
        const debt = this.getDebt();

        // always show total
        if (this.totalView) {
            this.totalView.textContent = total.toLocaleString();
        }

        //  CHỈ HIỆN KHI THANH TOÁN 1 PHẦN
        if (this.isPartial()) {

            if (this.paidView) {
                this.paidView.parentElement.classList.remove("d-none");
                this.paidView.textContent = paid.toLocaleString();
            }

            if (this.debtView) {
                this.debtView.parentElement.classList.remove("d-none");
                this.debtView.textContent = debt.toLocaleString();
            }

        } else {

            // ẨN HOÀN TOÀN
            if (this.paidView) {
                this.paidView.parentElement.classList.add("d-none");
            }

            if (this.debtView) {
                this.debtView.parentElement.classList.add("d-none");
            }
        }
    }
};