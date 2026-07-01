import { Api } from "../../../../common/api.js";
import { Notify } from "../../../../common/notify.js";
import { Messages } from "../../../../common/messages.js";
import { Product } from "./product.js";

export const Submit = {

    async create(url) {
        return this.send(url, "CREATE_SUCCESS");
    },

    async update(url) {
        return this.send(url, "UPDATE_SUCCESS");
    },

    async send(url, successKey) {

        const data = this.collect();

        const validate = this.validate(data);
        if (!validate.valid) {
            Notify.error(`Chưa được bạn! ${validate.message}`);
            return;
        }

        const json = await Api.post(url, data);

        if (json.success) {

            Notify.success(
                json.message || Messages.ORDER[successKey]
            );

            if (json.redirect) {
                location.href = json.redirect;
            }

            return;
        }

        Notify.error(
            `${json.message || Messages.COMMON.UNKNOWN_ERROR}`
        );
    },

    // =========================
    // COLLECT DATA
    // =========================
    collect() {

        const paidInput = document.getElementById("paid_amount");

        return {

            customer_id: document.getElementById("customer_id")?.value || "",
            description: document.getElementById("description")?.value || "",
            status: document.getElementById("status")?.value || "",
            payment: document.getElementById("payment")?.value || "",

            paid_amount: Number(paidInput?.value || 0),

            // ✅ FIXED: backend format chuẩn FIFO
            products: Product.getItems().map(p => ({
                product_id: p.product_id,
                quantity: Number(p.quantity),
                price: Number(p.price),

                // optional trace FIFO
                purchase_item_id: p.purchase_item_id || null
            }))
        };
    },

    // =========================
    // VALIDATION
    // =========================
    validate(data) {

        if (!data.customer_id) {
            return {
                valid: false,
                message: Messages.ORDER.CUSTOMER_REQUIRED
            };
        }

        if (!data.products.length) {
            return {
                valid: false,
                message: Messages.ORDER.PRODUCT_REQUIRED
            };
        }

        if (data.payment === "partial") {

            const total = data.products.reduce(
                (sum, i) => sum + (i.quantity * i.price),
                0
            );

            if (data.paid_amount > total) {
                return {
                    valid: false,
                    message: "Số tiền đã trả không được lớn hơn tổng tiền"
                };
            }
        }

        return { valid: true };
    }
};